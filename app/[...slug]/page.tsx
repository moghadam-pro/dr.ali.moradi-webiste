import type { Metadata } from "next";
import { SitePage } from "../site-page";
import { content, type InteriorPageData } from "../site-content";
import { blogLabels, findBlogPost } from "../blog-content";
import {
  clinicHubCopy, findTeamMember, galleryCollections, supplementalCoverImages,
  supplementalPages, type GalleryRoute,
} from "../structured-content";

type Locale = "en" | "fa" | "ar";
const siteUrl = process.env.NEXT_PUBLIC_SITE_URL ?? "https://dralimoradi.moghadam.pro";

function parseRoute(slug: string[]) {
  const locale: Locale = slug[0] === "fa" || slug[0] === "ar" ? slug[0] : "en";
  const routeSegments = locale === "en" ? slug : slug.slice(1);
  return { locale, page: routeSegments.join("/") || "home" };
}

export async function generateMetadata({ params }: { params: Promise<{ slug: string[] }> }): Promise<Metadata> {
  const { slug } = await params;
  const { locale, page } = parseRoute(slug);
  const post = page.startsWith("blog/") ? findBlogPost(page.slice(5)) : undefined;
  const member = page.startsWith("team/") ? findTeamMember(page.slice(5)) : undefined;
  const gallery = page in galleryCollections ? galleryCollections[page as GalleryRoute] : undefined;
  const pages = content[locale].pages as Record<string, InteriorPageData>;
  const pageData = supplementalPages[locale][page] ?? pages[page === "news" ? "blog" : page];
  const galleryCopy = gallery ? clinicHubCopy[locale] : undefined;
  const galleryTitle = gallery?.area === "clinic" ? galleryCopy?.clinicGalleryTitle : galleryCopy?.hospitalGalleryTitle;
  const galleryDescription = gallery?.area === "clinic" ? galleryCopy?.clinicGalleryIntro : galleryCopy?.hospitalGalleryIntro;
  const title = post?.title[locale] ?? member?.name[locale] ?? galleryTitle ?? (page === "blog" || page === "news" ? blogLabels[locale].title : pageData?.title) ?? "Dr. Ali Moradi";
  const description = post?.excerpt[locale] ?? member?.summary[locale] ?? galleryDescription ?? (page === "blog" || page === "news" ? blogLabels[locale].intro : pageData?.intro) ?? content[locale].heroDescription;
  const image = post?.image ?? member?.image ?? supplementalCoverImages[page] ?? "/social-banner.jpg";
  const path = locale === "en" ? `/${page === "home" ? "" : page}` : `/${locale}/${page === "home" ? "" : page}`;
  return {
    title,
    description,
    alternates: { canonical: path, languages: { en: page === "home" ? "/" : `/${page}`, "fa-IR": page === "home" ? "/fa" : `/fa/${page}`, ar: page === "home" ? "/ar" : `/ar/${page}` } },
    openGraph: { title, description, url: new URL(path, siteUrl).toString(), images: [{ url: new URL(image, siteUrl).toString() }] },
    twitter: { card: "summary_large_image", title, description, images: [new URL(image, siteUrl).toString()] },
  };
}

export default async function DynamicPage({
  params,
}: {
  params: Promise<{ slug: string[] }>;
}) {
  const { slug } = await params;
  const { locale, page } = parseRoute(slug);
  return <SitePage locale={locale} page={page} />;
}
