import { SitePage } from "../site-page";

type Locale = "en" | "fa" | "ar";

export default async function DynamicPage({
  params,
}: {
  params: Promise<{ slug: string[] }>;
}) {
  const { slug } = await params;
  const locale: Locale = slug[0] === "fa" || slug[0] === "ar" ? slug[0] : "en";
  const page = locale === "en" ? slug[0] : slug[1];
  return <SitePage locale={locale} page={page || "home"} />;
}
