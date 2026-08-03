import type { MetadataRoute } from "next";
import { pageSlugs } from "./site-content";

export default function sitemap(): MetadataRoute.Sitemap {
  const base = "https://dralimoradi.com";
  const pages = ["", ...pageSlugs, "contact"];
  const locales = ["", "fa", "ar"];
  return locales.flatMap((locale) => pages.map((page) => {
    const path = [locale, page].filter(Boolean).join("/");
    return {
      url: `${base}/${path}`,
      lastModified: new Date(),
      changeFrequency: page === "news" ? "weekly" as const : "monthly" as const,
      priority: page === "" ? 1 : .7,
    };
  }));
}
