import type { Metadata, Viewport } from "next";
import "@fontsource-variable/inter";
import "@fontsource-variable/vazirmatn";
import "@fontsource/scheherazade-new/400.css";
import "@fontsource/scheherazade-new/700.css";
import "./globals.css";

const siteUrl = process.env.NEXT_PUBLIC_SITE_URL ?? "https://dralimoradi.com";
const assetVersion = "20260803";

export const metadata: Metadata = {
  metadataBase: new URL(siteUrl),
  applicationName: "Dr. Ali Moradi",
  title: {
    default: "Dr. Ali Moradi | Hand & Upper Extremity Surgeon",
    template: "%s | Dr. Ali Moradi",
  },
  description:
    "Clinical care, research, medical innovation, and education in hand and upper-extremity surgery.",
  keywords: ["Dr. Ali Moradi", "hand surgeon", "upper extremity surgery", "orthopedics", "hand research", "medical innovation", "Mashhad"],
  authors: [{ name: "Dr. Ali Moradi", url: siteUrl }],
  creator: "Dr. Ali Moradi",
  publisher: "Dr. Ali Moradi",
  category: "healthcare",
  alternates: {
    canonical: "/",
    languages: { "en": "/", "fa-IR": "/fa", "ar": "/ar" },
  },
  icons: {
    icon: [
      { url: `/favicon.ico?v=${assetVersion}` },
      { url: `/icons/favicon-16x16.png?v=${assetVersion}`, sizes: "16x16", type: "image/png" },
      { url: `/icons/favicon-32x32.png?v=${assetVersion}`, sizes: "32x32", type: "image/png" },
      { url: `/icons/favicon-96x96.png?v=${assetVersion}`, sizes: "96x96", type: "image/png" },
    ],
    shortcut: `/favicon.ico?v=${assetVersion}`,
    apple: [
      { url: `/icons/apple-icon-180x180.png?v=${assetVersion}`, sizes: "180x180", type: "image/png" },
      { url: `/icons/apple-icon-152x152.png?v=${assetVersion}`, sizes: "152x152", type: "image/png" },
      { url: `/icons/apple-icon-120x120.png?v=${assetVersion}`, sizes: "120x120", type: "image/png" },
    ],
  },
  manifest: "/site.webmanifest",
  appleWebApp: { capable: true, title: "Dr. Ali Moradi", statusBarStyle: "default" },
  formatDetection: { telephone: false, address: false, email: false },
  referrer: "origin-when-cross-origin",
  other: {
    "msapplication-TileColor": "#4293C2",
    "msapplication-config": "/browserconfig.xml",
  },
  robots: { index: true, follow: true, googleBot: { index: true, follow: true, "max-image-preview": "large", "max-snippet": -1, "max-video-preview": -1 } },
  openGraph: {
    type: "website",
    url: siteUrl,
    siteName: "Dr. Ali Moradi",
    locale: "en_US",
    alternateLocale: ["fa_IR", "ar"],
    title: "Dr. Ali Moradi | Hand Surgery, Research & Innovation",
    description: "Clinical care, research, medical innovation, and education in hand and upper-extremity surgery.",
    images: [{ url: `/social-banner.jpg?v=${assetVersion}`, width: 1200, height: 630, alt: "Dr. Ali Moradi — advancing hand care through research and innovation", type: "image/jpeg" }],
  },
  twitter: {
    card: "summary_large_image",
    title: "Dr. Ali Moradi | Hand Surgery, Research & Innovation",
    description: "Clinical care, research, medical innovation, and education in hand and upper-extremity surgery.",
    images: [`/social-banner.jpg?v=${assetVersion}`],
  },
};

export const viewport: Viewport = {
  width: "device-width",
  initialScale: 1,
  maximumScale: 5,
  themeColor: "#4293C2",
  colorScheme: "light",
};

export default function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return (
    <html lang="en">
      <body>{children}</body>
    </html>
  );
}
