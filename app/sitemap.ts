import type { MetadataRoute } from "next";
import { blogPosts } from "./blog-content";
import { contentOverrideRoutes } from "./content-overrides";
import { pageSlugs } from "./site-content";
import { galleryCollections, supplementalPageRoutes, teamMembers } from "./structured-content";

export default function sitemap(): MetadataRoute.Sitemap {
  const base = process.env.NEXT_PUBLIC_SITE_URL ?? "https://dralimoradi.moghadam.pro";
  const pages = [...new Set([
    "", ...pageSlugs, "news", ...supplementalPageRoutes, ...contentOverrideRoutes,
    ...Object.keys(galleryCollections),
    ...teamMembers.map((member) => `team/${member.slug}`),
    ...blogPosts.map((post) => `blog/${post.slug}`),
  ])];
  const locales = ["", "fa", "ar"];
  return locales.flatMap((locale) => pages.map((page) => {
    const path = [locale, page].filter(Boolean).join("/");
    return {
      url: `${base}/${path}`,
      lastModified: new Date(),
      changeFrequency: page === "blog" || page.startsWith("blog/") ? "weekly" as const : "monthly" as const,
      priority: page === "" ? 1 : page.startsWith("blog/") ? .6 : .7,
    };
  }));
}
