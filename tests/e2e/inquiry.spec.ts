import { test, expect, Page } from '@playwright/test';

test.describe('Inquiry System', () => {
  let unitSlug: string | null = null;

  // Helper to get a unit slug from the listing page
  async function getFirstUnitSlug(page: Page): Promise<string | null> {
    await page.goto('/units');

    // Look for unit card links
    const unitLink = page.locator('a[href*="/units/"]').first();
    const exists = await unitLink.isVisible().catch(() => false);

    if (!exists) {
      return null;
    }

    const href = await unitLink.getAttribute('href');
    if (href) {
      const match = href.match(/\/units\/([^\/]+)$/);
      return match ? match[1] : null;
    }
    return null;
  }

  test.beforeAll(async ({ browser }) => {
    const page = await browser.newPage();
    unitSlug = await getFirstUnitSlug(page);
    await page.close();
  });

  test('should open inquiry modal', async ({ page }) => {
    test.skip(!unitSlug, 'No units available for testing');

    await page.goto(`/units/${unitSlug}`);

    // Click interest button
    await page.click('button:has-text("I am Interested"), button:has-text("أنا مهتم")');

    // Modal should be visible
    await expect(page.locator('text=/Send Inquiry|إرسال استفسار/')).toBeVisible();
  });

  test('should show form fields in modal', async ({ page }) => {
    test.skip(!unitSlug, 'No units available for testing');

    await page.goto(`/units/${unitSlug}`);

    // Open modal
    await page.click('button:has-text("I am Interested"), button:has-text("أنا مهتم")');

    // Check form fields
    await expect(page.locator('input[type="text"]').first()).toBeVisible();
    await expect(page.locator('input[type="tel"]')).toBeVisible();
    await expect(page.locator('input[type="email"]')).toBeVisible();
    await expect(page.locator('textarea')).toBeVisible();
  });

  test('should validate name field as required', async ({ page }) => {
    test.skip(!unitSlug, 'No units available for testing');

    await page.goto(`/units/${unitSlug}`);

    // Open modal
    await page.click('button:has-text("I am Interested"), button:has-text("أنا مهتم")');

    // Try to submit with only phone
    await page.fill('input[type="tel"]', '0123456789');

    // Get submit button and try to submit
    const submitButton = page.locator('form button[type="submit"]');
    await submitButton.click();

    // Form should still be visible (HTML5 validation prevents submit)
    await expect(page.locator('input[type="text"]').first()).toBeVisible();
  });

  test('should validate phone field as required', async ({ page }) => {
    test.skip(!unitSlug, 'No units available for testing');

    await page.goto(`/units/${unitSlug}`);

    // Open modal
    await page.click('button:has-text("I am Interested"), button:has-text("أنا مهتم")');

    // Fill only name
    await page.fill('input[type="text"]', 'Test User');

    // Get submit button and try to submit
    const submitButton = page.locator('form button[type="submit"]');
    await submitButton.click();

    // Form should still be visible (HTML5 validation prevents submit)
    await expect(page.locator('input[type="tel"]')).toBeVisible();
  });

  test('should allow optional email field', async ({ page }) => {
    test.skip(!unitSlug, 'No units available for testing');

    await page.goto(`/units/${unitSlug}`);

    // Open modal
    await page.click('button:has-text("I am Interested"), button:has-text("أنا مهتم")');

    // Fill required fields only
    await page.fill('input[type="text"]', 'Test User');
    await page.fill('input[type="tel"]', '0123456789');

    // Email can be empty - form should be valid
    const emailInput = page.locator('input[type="email"]');
    const required = await emailInput.getAttribute('required');

    // Email should not be required
    expect(required).toBeNull();
  });

  test('should allow optional message field', async ({ page }) => {
    test.skip(!unitSlug, 'No units available for testing');

    await page.goto(`/units/${unitSlug}`);

    // Open modal
    await page.click('button:has-text("I am Interested"), button:has-text("أنا مهتم")');

    // Message can be empty
    const messageInput = page.locator('textarea');
    const required = await messageInput.getAttribute('required');

    // Message should not be required
    expect(required).toBeNull();
  });

  test('should close modal with close button', async ({ page }) => {
    test.skip(!unitSlug, 'No units available for testing');

    await page.goto(`/units/${unitSlug}`);

    // Open modal
    await page.click('button:has-text("I am Interested"), button:has-text("أنا مهتم")');
    await expect(page.locator('text=/Send Inquiry|إرسال استفسار/')).toBeVisible();

    // Click close button (X icon)
    await page.click('button svg[class*="w-6"]');

    // Modal should be hidden
    await expect(page.locator('h3:has-text("Send Inquiry"), h3:has-text("إرسال استفسار")')).not.toBeVisible();
  });

  test('should close modal with Escape key', async ({ page }) => {
    test.skip(!unitSlug, 'No units available for testing');

    await page.goto(`/units/${unitSlug}`);

    // Open modal
    await page.click('button:has-text("I am Interested"), button:has-text("أنا مهتم")');
    await expect(page.locator('text=/Send Inquiry|إرسال استفسار/')).toBeVisible();

    // Press Escape
    await page.keyboard.press('Escape');

    // Modal should be hidden
    await expect(page.locator('h3:has-text("Send Inquiry"), h3:has-text("إرسال استفسار")')).not.toBeVisible();
  });

  test('should show loading state on submit', async ({ page }) => {
    test.skip(!unitSlug, 'No units available for testing');

    await page.goto(`/units/${unitSlug}`);

    // Open modal
    await page.click('button:has-text("I am Interested"), button:has-text("أنا مهتم")');

    // Fill form
    await page.fill('input[type="text"]', 'Test User');
    await page.fill('input[type="tel"]', '0123456789');

    // Start form submission and check for loading state
    const submitButton = page.locator('form button[type="submit"]');

    // Listen for the button text to change
    await submitButton.click();

    // Should show loading or success state
    // (Either loading spinner or success message depending on speed)
    await expect(page.locator('text=/Sending|جاري الإرسال|Thank You|شكراً/').first()).toBeVisible({ timeout: 10000 });
  });

  test('should display success message after submission', async ({ page }) => {
    test.skip(!unitSlug, 'No units available for testing');

    await page.goto(`/units/${unitSlug}`);

    // Open modal
    await page.click('button:has-text("I am Interested"), button:has-text("أنا مهتم")');

    // Fill form with valid data
    await page.fill('input[type="text"]', 'Test User');
    await page.fill('input[type="tel"]', '01234567890');
    await page.fill('input[type="email"]', 'test@example.com');
    await page.fill('textarea', 'I am interested in this property.');

    // Submit form
    await page.click('form button[type="submit"]');

    // Wait for success message
    await expect(page.locator('text=/Thank You|شكراً لك/')).toBeVisible({ timeout: 10000 });
  });
});
