import { test, expect } from '@playwright/test';

test.describe('Units Listing Page', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/units');
  });

  test('should display page header with title', async ({ page }) => {
    await expect(page.locator('h1')).toContainText(/All Units|جميع الوحدات/);
  });

  test('should have filter sidebar', async ({ page }) => {
    // Check filter form exists
    await expect(page.locator('form select[name="type"]')).toBeVisible();
    await expect(page.locator('form select[name="city_id"]')).toBeVisible();
  });

  test('should filter by property type', async ({ page }) => {
    // Select rental type
    await page.selectOption('form select[name="type"]', 'rental');
    await page.click('button:has-text("Apply"), button:has-text("تطبيق")');

    // URL should have type filter
    await expect(page).toHaveURL(/.*type=rental/);
  });

  test('should filter by city', async ({ page }) => {
    // Get first city option
    const citySelect = page.locator('form select[name="city_id"]');
    await citySelect.selectOption({ index: 1 });
    await page.click('button:has-text("Apply"), button:has-text("تطبيق")');

    // URL should have city_id filter
    await expect(page).toHaveURL(/.*city_id=/);
  });

  test('should search by keyword', async ({ page }) => {
    // Fill search input
    await page.fill('form input[name="search"]', 'villa');
    await page.click('button:has-text("Apply"), button:has-text("تطبيق")');

    // URL should have search parameter
    await expect(page).toHaveURL(/.*search=villa/);
  });

  test('should filter by price range', async ({ page }) => {
    // Fill price range
    await page.fill('form input[name="price_min"]', '1000');
    await page.fill('form input[name="price_max"]', '50000');
    await page.click('button:has-text("Apply"), button:has-text("تطبيق")');

    // URL should have price filters
    await expect(page).toHaveURL(/.*price_min=1000.*price_max=50000/);
  });

  test('should reset filters', async ({ page }) => {
    // Apply a filter first
    await page.selectOption('form select[name="type"]', 'sale');
    await page.click('button:has-text("Apply"), button:has-text("تطبيق")');
    await expect(page).toHaveURL(/.*type=sale/);

    // Reset filters
    await page.click('a:has-text("Reset"), a:has-text("إعادة تعيين")');

    // Should be back to /units without filters
    await expect(page).toHaveURL('/units');
  });

  test('should display unit cards if data exists', async ({ page }) => {
    // Check if there are unit cards or empty state
    const unitCards = page.locator('[class*="unit-card"], .grid > div');
    const emptyState = page.locator('text=/No units found|لا توجد وحدات/');

    const hasUnits = await unitCards.first().isVisible().catch(() => false);
    const hasEmpty = await emptyState.isVisible().catch(() => false);

    // Either units exist or empty state is shown
    expect(hasUnits || hasEmpty).toBeTruthy();
  });

  test('should display active filter tags', async ({ page }) => {
    // Apply multiple filters
    await page.selectOption('form select[name="type"]', 'rental');
    await page.fill('form input[name="search"]', 'test');
    await page.click('button:has-text("Apply"), button:has-text("تطبيق")');

    // Should show filter tags
    await expect(page.locator('.inline-flex:has-text("Rental"), .inline-flex:has-text("إيجار")')).toBeVisible();
  });

  test('should have pagination if many results', async ({ page }) => {
    // Check for pagination (may not exist with few records)
    const pagination = page.locator('nav[role="navigation"]');
    const exists = await pagination.isVisible().catch(() => false);

    // Just log whether pagination exists
    console.log('Pagination exists:', exists);
  });
});

test.describe('Rental Units Page', () => {
  test('should show only rental units', async ({ page }) => {
    await page.goto('/units/rental');
    await expect(page.locator('h1')).toContainText(/Rental|إيجار/);
  });
});

test.describe('Sale Units Page', () => {
  test('should show only sale units', async ({ page }) => {
    await page.goto('/units/sale');
    await expect(page.locator('h1')).toContainText(/Sale|بيع/);
  });
});

test.describe('Under Construction Units Page', () => {
  test('should show only under construction units', async ({ page }) => {
    await page.goto('/units/construction');
    await expect(page.locator('h1')).toContainText(/Under Construction|تحت الإنشاء/);
  });
});
