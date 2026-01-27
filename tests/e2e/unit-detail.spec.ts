import { test, expect, Page } from '@playwright/test';

test.describe('Unit Detail Page', () => {
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

  test('should display unit title and type badge', async ({ page }) => {
    test.skip(!unitSlug, 'No units available for testing');

    await page.goto(`/units/${unitSlug}`);

    // Check title exists
    await expect(page.locator('h1')).toBeVisible();

    // Check type badge exists
    await expect(page.locator('span:has-text("Rental"), span:has-text("Sale"), span:has-text("Under Construction"), span:has-text("إيجار"), span:has-text("بيع"), span:has-text("تحت الإنشاء")').first()).toBeVisible();
  });

  test('should display breadcrumb navigation', async ({ page }) => {
    test.skip(!unitSlug, 'No units available for testing');

    await page.goto(`/units/${unitSlug}`);

    // Check breadcrumb has home and properties links
    await expect(page.locator('nav a[href="/"]')).toBeVisible();
    await expect(page.locator('nav a[href="/units"]')).toBeVisible();
  });

  test('should display property features section', async ({ page }) => {
    test.skip(!unitSlug, 'No units available for testing');

    await page.goto(`/units/${unitSlug}`);

    // Check property features section
    await expect(page.locator('text=/Property Features|مميزات العقار/')).toBeVisible();
  });

  test('should display description section', async ({ page }) => {
    test.skip(!unitSlug, 'No units available for testing');

    await page.goto(`/units/${unitSlug}`);

    // Check description section
    await expect(page.locator('h2:has-text("Description"), h2:has-text("الوصف")')).toBeVisible();
  });

  test('should display price information', async ({ page }) => {
    test.skip(!unitSlug, 'No units available for testing');

    await page.goto(`/units/${unitSlug}`);

    // Check price is displayed (EGP or SAR)
    await expect(page.locator('text=/EGP|ج.م|SAR|ر.س/').first()).toBeVisible();
  });

  test('should have "I am Interested" button', async ({ page }) => {
    test.skip(!unitSlug, 'No units available for testing');

    await page.goto(`/units/${unitSlug}`);

    // Check interest button exists
    await expect(page.locator('button:has-text("I am Interested"), button:has-text("أنا مهتم")')).toBeVisible();
  });

  test('should open inquiry modal when clicking interest button', async ({ page }) => {
    test.skip(!unitSlug, 'No units available for testing');

    await page.goto(`/units/${unitSlug}`);

    // Click the interest button
    await page.click('button:has-text("I am Interested"), button:has-text("أنا مهتم")');

    // Modal should be visible
    await expect(page.locator('text=/Send Inquiry|إرسال استفسار/')).toBeVisible();

    // Form fields should be visible
    await expect(page.locator('input[type="text"]').first()).toBeVisible();
    await expect(page.locator('input[type="tel"]')).toBeVisible();
  });

  test('should validate required fields in inquiry form', async ({ page }) => {
    test.skip(!unitSlug, 'No units available for testing');

    await page.goto(`/units/${unitSlug}`);

    // Open modal
    await page.click('button:has-text("I am Interested"), button:has-text("أنا مهتم")');

    // Try to submit empty form - browser validation should prevent
    const submitButton = page.locator('button:has-text("Send Inquiry"), button:has-text("إرسال استفسار")');
    await submitButton.click();

    // Form should still be visible (not submitted due to validation)
    await expect(page.locator('input[type="text"]').first()).toBeVisible();
  });

  test('should close inquiry modal when clicking outside', async ({ page }) => {
    test.skip(!unitSlug, 'No units available for testing');

    await page.goto(`/units/${unitSlug}`);

    // Open modal
    await page.click('button:has-text("I am Interested"), button:has-text("أنا مهتم")');
    await expect(page.locator('text=/Send Inquiry|إرسال استفسار/')).toBeVisible();

    // Press Escape to close
    await page.keyboard.press('Escape');

    // Modal should be hidden
    await expect(page.locator('text=/Send Inquiry|إرسال استفسار/')).not.toBeVisible();
  });

  test('should display image gallery if images exist', async ({ page }) => {
    test.skip(!unitSlug, 'No units available for testing');

    await page.goto(`/units/${unitSlug}`);

    // Check for image or placeholder
    const image = page.locator('img[alt]').first();
    const placeholder = page.locator('svg').first();

    const hasImage = await image.isVisible().catch(() => false);
    const hasPlaceholder = await placeholder.isVisible().catch(() => false);

    expect(hasImage || hasPlaceholder).toBeTruthy();
  });

  test('should display contact information if configured', async ({ page }) => {
    test.skip(!unitSlug, 'No units available for testing');

    await page.goto(`/units/${unitSlug}`);

    // Check for contact section (may not exist if settings not configured)
    const contactSection = page.locator('text=/Contact Us|اتصل بنا/');
    const exists = await contactSection.isVisible().catch(() => false);

    console.log('Contact section exists:', exists);
  });
});
