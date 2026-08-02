import type { Metadata } from "next";
import "@fontsource-variable/manrope";
import "@fontsource-variable/vazirmatn";
import "./globals.css";

export const metadata: Metadata = {
  metadataBase: new URL("https://dralimoradi.com"),
  title: {
    default: "Dr. Ali Moradi | Hand & Upper Extremity Surgeon",
    template: "%s | Dr. Ali Moradi",
  },
  description:
    "Clinical care, research, medical innovation, and education in hand and upper-extremity surgery.",
  icons: {
    icon: "/brand/logo-mark.svg",
    shortcut: "/brand/logo-mark.svg",
  },
  openGraph: {
    type: "website",
    title: "Dr. Ali Moradi | Hand Surgery, Research & Innovation",
    description: "Clinical care, research, medical innovation, and education in hand and upper-extremity surgery.",
    images: [{ url: "/og.png", width: 1200, height: 630, alt: "Dr. Ali Moradi — Hand Surgery, Research and Innovation" }],
  },
  twitter: {
    card: "summary_large_image",
    title: "Dr. Ali Moradi | Hand Surgery, Research & Innovation",
    description: "Clinical care, research, medical innovation, and education in hand and upper-extremity surgery.",
    images: ["/og.png"],
  },
};

export default function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return (
    <html lang="en">
      <body>{children}</body>
    </html>
  );
}
