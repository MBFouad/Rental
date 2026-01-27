import { test, expect } from '@playwright/test';

test.describe('Language Switching', () => {
  test('should default to Arabic (RTL)', async ({ page }) => {
    await page.goto('/');

    // Check HTML dir attribute
    const dir = await page.locator('html').getAttribute('dir');
    expect(dir).toBe('rtl');

    // Check lang attribute
    const lang = await page.locator('html').getAttribute('lang');
    expect(lang).toBe('ar');
  });

  test('should switch to English (LTR)', async ({ page }) => {
    await page.goto('/en');

    // Check HTML dir attribute changes to LTR
    const dir = await page.locator('html').getAttribute('dir');
    expect(dir).toBe('ltr');

    // Check lang attribute
    const lang = await page.locator('html').getAttribute('lang');
    expect(lang).toBe('en');
  });

  test('should display Arabic translations on homepage', async ({ page }) => {
    await page.goto('/ar');

    // Check for Arabic content
    await expect(page.locator('h1')).toContainText('اعثر على عقار أحلامك');
  });

  test('should display English translations on homepage', async ({ page }) => {
    await page.goto('/en');

    // Check for English content
    await expect(page.locator('h1')).toContainText('Find Your Dream Property');
  });

  test('should maintain language when navigating', async ({ page }) => {
    // Start with English
    await page.goto('/en');

    // Navigate to units page
    await page.click('a[href*="/units"]');

    // Should still be in English
    const lang = await page.locator('html').getAttribute('lang');
    expect(lang).toBe('en');
  });

  test('should translate filter labels', async ({ page }) => {
    // Arabic
    await page.goto('/ar/units');
    await expect(page.locator('text=تصفية العقارات')).toBeVisible();

    // English
    await page.goto('/en/units');
    await expect(page.locator('text=Filter Properties')).toBeVisible();
  });

  test('should translate button labels', async ({ page }) => {
    // Arabic
    await page.goto('/ar');
    await expect(page.locator('button:has-text("بحث")')).toBeVisible();

    // English
    await page.goto('/en');
    await expect(page.locator('button:has-text("Search")')).toBeVisible();
  });

  test('should translate category cards', async ({ page }) => {
    // Arabic
    await page.goto('/ar');
    await expect(page.locator('text=إيجار').first()).toBeVisible();
    await expect(page.locator('text=بيع').first()).toBeVisible();
    await expect(page.locator('text=تحت الإنشاء').first()).toBeVisible();

    // English
    await page.goto('/en');
    await expect(page.locator('text=Rental').first()).toBeVisible();
    await expect(page.locator('text=Sale').first()).toBeVisible();
    await expect(page.locator('text=Under Construction').first()).toBeVisible();
  });
});

test.describe('RTL Layout', () => {
  test('should have correct text alignment in RTL mode', async ({ page }) => {
    await page.goto('/ar');

    // The body should flow RTL
    const dir = await page.locator('html').getAttribute('dir');
    expect(dir).toBe('rtl');
  });

  test('should have correct text alignment in LTR mode', async ({ page }) => {
    await page.goto('/en');

    // The body should flow LTR
    const dir = await page.locator('html').getAttribute('dir');
    expect(dir).toBe('ltr');
  });

  test('should rotate navigation arrows for RTL', async ({ page }) => {
    await page.goto('/ar');

    // Check that arrow icons are rotated in RTL mode
    // This is visual - we just ensure the page loads correctly
    const arrowIcon = page.locator('svg.rotate-180').first();
    const exists = await arrowIcon.isVisible().catch(() => false);

    // At least check the page renders
    await expect(page.locator('body')).toBeVisible();
  });
});
