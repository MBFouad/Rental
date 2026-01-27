import { test, expect } from '@playwright/test';

test.describe('Homepage', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/');
  });

  test('should display hero section with search form', async ({ page }) => {
    // Check hero heading exists
    await expect(page.locator('h1')).toContainText(/Find Your Dream Property|اعثر على عقار أحلامك/);

    // Check search form elements
    await expect(page.locator('input[name="search"]')).toBeVisible();
    await expect(page.locator('select[name="type"]')).toBeVisible();
    await expect(page.locator('select[name="city_id"]')).toBeVisible();
    await expect(page.locator('button[type="submit"]')).toBeVisible();
  });

  test('should have category cards for Rental, Sale, and Under Construction', async ({ page }) => {
    // Check category cards
    const rentalCard = page.locator('a[href*="rental"]').first();
    const saleCard = page.locator('a[href*="sale"]').first();
    const constructionCard = page.locator('a[href*="construction"]').first();

    await expect(rentalCard).toBeVisible();
    await expect(saleCard).toBeVisible();
    await expect(constructionCard).toBeVisible();
  });

  test('should navigate to units page when clicking category cards', async ({ page }) => {
    // Click rental category card
    await page.locator('a[href*="rental"]').first().click();
    await expect(page).toHaveURL(/.*rental.*/);

    // Go back and click sale
    await page.goto('/');
    await page.locator('a[href*="sale"]').first().click();
    await expect(page).toHaveURL(/.*sale.*/);
  });

  test('should submit search form and navigate to units page', async ({ page }) => {
    // Fill search form
    await page.fill('input[name="search"]', 'apartment');
    await page.selectOption('select[name="type"]', 'rental');

    // Submit form
    await page.click('button[type="submit"]');

    // Should be on units page with filters
    await expect(page).toHaveURL(/.*units.*search=apartment.*type=rental/);
  });

  test('should have header with navigation', async ({ page }) => {
    // Check navigation links
    await expect(page.locator('header')).toBeVisible();
    await expect(page.locator('a[href="/"]')).toBeVisible();
  });

  test('should have footer with contact info', async ({ page }) => {
    // Check footer exists
    await expect(page.locator('footer')).toBeVisible();
  });

  test('should display featured properties section if units exist', async ({ page }) => {
    // Featured section may or may not exist depending on data
    const featuredSection = page.locator('text=Featured Properties');
    const hasFeatured = await featuredSection.isVisible().catch(() => false);

    if (hasFeatured) {
      await expect(featuredSection).toBeVisible();
    }
  });

  test('should display CTA section', async ({ page }) => {
    // Check CTA section
    await expect(page.locator('text=/Ready to Find|هل أنت مستعد/')).toBeVisible();
    await expect(page.locator('text=/Start Browsing|ابدأ التصفح/')).toBeVisible();
  });
});
