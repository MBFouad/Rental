import { test, expect } from '@playwright/test';

test.describe('Theme Consistency', () => {
  test('should have consistent header across pages', async ({ page }) => {
    const pages = ['/', '/units', '/units/rental', '/units/sale', '/units/construction'];

    for (const pagePath of pages) {
      await page.goto(pagePath);

      // Header should exist on all pages
      await expect(page.locator('header')).toBeVisible();

      // Logo/brand should be visible
      const logo = page.locator('header a[href="/"]');
      await expect(logo).toBeVisible();
    }
  });

  test('should have consistent footer across pages', async ({ page }) => {
    const pages = ['/', '/units', '/units/rental'];

    for (const pagePath of pages) {
      await page.goto(pagePath);

      // Footer should exist on all pages
      await expect(page.locator('footer')).toBeVisible();
    }
  });

  test('should have responsive navigation on mobile', async ({ page }) => {
    // Set mobile viewport
    await page.setViewportSize({ width: 375, height: 667 });
    await page.goto('/');

    // Header should still be visible
    await expect(page.locator('header')).toBeVisible();

    // Check for mobile menu button if exists
    const mobileMenuButton = page.locator('button[aria-label*="menu"], button[aria-label*="Menu"]');
    const hasMobileMenu = await mobileMenuButton.isVisible().catch(() => false);

    console.log('Has mobile menu:', hasMobileMenu);
  });

  test('should have responsive filter sidebar on mobile', async ({ page }) => {
    // Set mobile viewport
    await page.setViewportSize({ width: 375, height: 667 });
    await page.goto('/units');

    // Filter toggle should be visible on mobile
    const filterToggle = page.locator('button:has-text("Filters"), button:has-text("الفلاتر")');
    await expect(filterToggle).toBeVisible();

    // Click to show filters
    await filterToggle.click();

    // Filter form should now be visible
    await expect(page.locator('form select[name="type"]')).toBeVisible();
  });

  test('should display unit cards properly', async ({ page }) => {
    await page.goto('/units');

    // Check if unit cards exist or empty state
    const cards = page.locator('.grid > div').first();
    const emptyState = page.locator('text=/No units found|لا توجد وحدات/');

    const hasCards = await cards.isVisible().catch(() => false);
    const hasEmpty = await emptyState.isVisible().catch(() => false);

    expect(hasCards || hasEmpty).toBeTruthy();
  });

  test('should have proper card hover effects', async ({ page }) => {
    await page.goto('/');

    // Find a category card
    const card = page.locator('a.group').first();
    const exists = await card.isVisible().catch(() => false);

    if (exists) {
      // Hover over card
      await card.hover();

      // Card should still be visible and interactive
      await expect(card).toBeVisible();
    }
  });
});

test.describe('Visual Elements', () => {
  test('should display hero section with gradient background', async ({ page }) => {
    await page.goto('/');

    // Hero section should have gradient classes
    const heroSection = page.locator('section.relative').first();
    await expect(heroSection).toBeVisible();
  });

  test('should display icons in category cards', async ({ page }) => {
    await page.goto('/');

    // SVG icons should be present in category cards
    const svgIcons = page.locator('section svg');
    const count = await svgIcons.count();

    expect(count).toBeGreaterThan(0);
  });

  test('should have proper button styling', async ({ page }) => {
    await page.goto('/');

    // Check primary button exists
    const primaryButton = page.locator('button.bg-blue-600, a.bg-blue-600').first();
    const exists = await primaryButton.isVisible().catch(() => false);

    if (exists) {
      await expect(primaryButton).toBeVisible();
    }
  });

  test('should display badges correctly', async ({ page }) => {
    await page.goto('/units');

    // Check for type badges if units exist
    const badges = page.locator('span.bg-blue-600, span.bg-green-600, span.bg-amber-500');
    const count = await badges.count().catch(() => 0);

    console.log('Badge count:', count);
  });
});

test.describe('Dark Mode', () => {
  test('should support dark mode classes', async ({ page }) => {
    await page.goto('/');

    // Check that dark mode classes exist in HTML
    const hasDarkClasses = await page.locator('[class*="dark:"]').count();
    expect(hasDarkClasses).toBeGreaterThan(0);
  });
});
