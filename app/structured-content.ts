import type { InteriorPageData, Locale } from "./site-content";

type LocalizedText = Record<Locale, string>;

export type PageTemplateName = "interior" | "single-post" | "about" | "blank" | "form";

export const pageTemplateRegistry: Record<string, PageTemplateName> = {
  contact: "form",
  blog: "interior",
  news: "interior",
  about: "interior",
};

export function resolvePageTemplate(page: string): PageTemplateName {
  if (page.startsWith("blog/")) return "single-post";
  return pageTemplateRegistry[page] ?? "interior";
}

export type TeamArea = "clinic" | "research" | "innovation";

export type TeamMember = {
  slug: string;
  name: LocalizedText;
  role: LocalizedText;
  summary: LocalizedText;
  bio: LocalizedText;
  image: string;
  areas: TeamArea[];
};

export const teamMembers: TeamMember[] = [
  {
    slug: "ali-moradi",
    name: { en: "Dr. Ali Moradi", fa: "دکتر علی مرادی", ar: "الدكتور علي مرادي" },
    role: { en: "Hand surgeon and clinical lead", fa: "جراح دست و مدیر تیم بالینی", ar: "جراح اليد وقائد الفريق السريري" },
    summary: {
      en: "Leads specialist hand and upper-extremity care and connects clinical decisions with research, engineering, and rehabilitation.",
      fa: "هدایت مراقبت تخصصی دست و اندام فوقانی و پیوند تصمیم‌های بالینی با پژوهش، مهندسی و توان‌بخشی.",
      ar: "يقود رعاية اليد والطرف العلوي ويربط القرار السريري بالبحث والهندسة والتأهيل.",
    },
    bio: {
      en: "Dr. Moradi is an associate professor of orthopedics and a hand and upper-extremity surgeon. His work spans specialist care, clinical research, medical engineering, innovation, and professional education.",
      fa: "دکتر مرادی دانشیار ارتوپدی و جراح دست و اندام فوقانی است. فعالیت او مراقبت تخصصی، پژوهش بالینی، مهندسی پزشکی، نوآوری و آموزش حرفه‌ای را به هم متصل می‌کند.",
      ar: "الدكتور مرادي أستاذ مشارك في جراحة العظام وجراح لليد والطرف العلوي، ويربط عمله الرعاية المتخصصة بالبحث والهندسة الطبية والابتكار والتعليم.",
    },
    image: "/media/appointments/doctor.jpg",
    areas: ["clinic", "research", "innovation"],
  },
  {
    slug: "mona-meybodi",
    name: { en: "Dr. Mona Meybodi", fa: "دکتر مونا میبدی", ar: "الدكتورة مونا ميبدي" },
    role: { en: "Specialist clinic manager", fa: "مدیر کلینیک تخصصی", ar: "مديرة العيادة التخصصية" },
    summary: {
      en: "Coordinates the specialist clinic workflow and helps connect patient needs with the clinical team.",
      fa: "هماهنگی گردش کار کلینیک تخصصی و ارتباط نیازهای بیماران با تیم درمان.",
      ar: "تنسق سير عمل العيادة وتربط احتياجات المرضى بالفريق الطبي.",
    },
    bio: {
      en: "Dr. Meybodi works with the upper-extremity registry and coordinates the specialist hand-surgery and orthopedics clinic. Her role focuses on continuity, organization, and patient communication.",
      fa: "دکتر میبدی همکاری خود را با پروژه ثبت اندام فوقانی آغاز کرد و اکنون مدیریت کلینیک تخصصی جراحی دست و ارتوپدی را بر عهده دارد. تمرکز نقش او بر پیوستگی خدمات، سازمان‌دهی و ارتباط با بیمار است.",
      ar: "بدأت الدكتورة ميبدي تعاونها في مشروع سجل الطرف العلوي، وتنسق حالياً عيادة جراحة اليد والعظام مع التركيز على استمرارية الرعاية والتواصل.",
    },
    image: "/media/team/mona-meybodi.jpg",
    areas: ["clinic"],
  },
  {
    slug: "mahsa-jafari",
    name: { en: "Mahsa Jafari", fa: "مهسا جعفری", ar: "مهسا جعفري" },
    role: { en: "Reception and finance coordinator", fa: "هماهنگ‌کننده پذیرش و امور مالی", ar: "منسقة الاستقبال والشؤون المالية" },
    summary: {
      en: "Supports appointment coordination, reception, and the first point of communication with the clinic.",
      fa: "هماهنگی پذیرش، زمان‌بندی و نخستین مسیر ارتباطی مراجعه‌کنندگان با کلینیک.",
      ar: "تدعم تنسيق المواعيد والاستقبال ونقطة التواصل الأولى مع العيادة.",
    },
    bio: {
      en: "Mahsa Jafari coordinates reception and financial administration. Her work supports clear scheduling, responsive communication, and an organized arrival experience.",
      fa: "مهسا جعفری هماهنگی پذیرش و امور مالی را انجام می‌دهد. فعالیت او به زمان‌بندی روشن، پاسخ‌گویی و تجربه منظم مراجعه کمک می‌کند.",
      ar: "تنسق مهسا جعفري الاستقبال والإدارة المالية، وتدعم جدولة واضحة وتواصلاً منظماً مع المراجعين.",
    },
    image: "/media/team/mahsa-jafari.jpg",
    areas: ["clinic"],
  },
  {
    slug: "afsaneh-jahani",
    name: { en: "Dr. Afsaneh Jahani", fa: "دکتر افسانه جهانی", ar: "الدكتورة أفسانة جهاني" },
    role: { en: "Postdoctoral bone and joint researcher", fa: "پژوهشگر پسادکتری استخوان و مفاصل", ar: "باحثة ما بعد الدكتوراه في العظام والمفاصل" },
    summary: {
      en: "Works across biomaterials, tissue engineering, and interdisciplinary orthopedic research.",
      fa: "فعال در حوزه مواد زیستی، مهندسی بافت و پژوهش میان‌رشته‌ای ارتوپدی.",
      ar: "تعمل في المواد الحيوية وهندسة الأنسجة والبحث العظمي متعدد التخصصات.",
    },
    bio: {
      en: "Dr. Jahani is a biomedical-engineering researcher whose work includes nanomaterials, tissue-engineering scaffolds, drug delivery, and orthopedic applications.",
      fa: "دکتر جهانی پژوهشگر مهندسی پزشکی است و در زمینه نانومواد، داربست‌های مهندسی بافت، دارورسانی و کاربردهای ارتوپدی فعالیت می‌کند.",
      ar: "الدكتورة جهاني باحثة في الهندسة الطبية وتشمل أعمالها المواد النانوية وسقالات هندسة الأنسجة وتوصيل الدواء وتطبيقات العظام.",
    },
    image: "/media/team/afsaneh-jahani.jpg",
    areas: ["research", "innovation"],
  },
  {
    slug: "maedeh-sharafoddin",
    name: { en: "Maedeh Sharafoddin", fa: "مائده شرف‌الدین", ar: "مائدة شرف الدين" },
    role: { en: "Clinical researcher and epidemiologist", fa: "محقق بالینی و اپیدمیولوژیست", ar: "باحثة سريرية واختصاصية وبائيات" },
    summary: {
      en: "Supports clinical study design, registry work, evidence review, and outcome-focused research.",
      fa: "همکاری در طراحی مطالعات بالینی، رجیستری، مرور شواهد و پژوهش پیامدمحور.",
      ar: "تدعم تصميم الدراسات السريرية والسجلات ومراجعة الدليل وبحوث النتائج.",
    },
    bio: {
      en: "Maedeh Sharafoddin is a clinical researcher with training in epidemiology and public health. Her work supports structured clinical evidence and outcome measurement.",
      fa: "مائده شرف‌الدین محقق بالینی با آموزش تخصصی در اپیدمیولوژی و سلامت عمومی است و در تولید شواهد ساختاریافته و سنجش پیامدها همکاری می‌کند.",
      ar: "مائدة شرف الدين باحثة سريرية متخصصة في الوبائيات والصحة العامة وتدعم بناء الدليل وقياس النتائج.",
    },
    image: "/media/team/maedeh-sharafoddin.jpg",
    areas: ["research"],
  },
  {
    slug: "naeemeh-kalali",
    name: { en: "Dr. Naeemeh Kalali", fa: "دکتر نعیمه کلالی", ar: "الدكتورة نعيمة كلالي" },
    role: { en: "Laboratory specialist and researcher", fa: "کارشناس آزمایشگاه و پژوهشگر", ar: "اختصاصية مختبر وباحثة" },
    summary: {
      en: "Contributes laboratory expertise in biology and medical microbiology to bone and joint research.",
      fa: "همکاری تخصصی آزمایشگاهی در زیست‌شناسی و میکروبیولوژی پزشکی برای پژوهش استخوان و مفاصل.",
      ar: "تقدم خبرة مختبرية في الأحياء والأحياء الدقيقة الطبية لبحوث العظام والمفاصل.",
    },
    bio: {
      en: "Dr. Kalali supports laboratory research with a background in biology and medical microbiology. Her work contributes to reliable experimental workflows and interdisciplinary studies.",
      fa: "دکتر کلالی با پیشینه زیست‌شناسی و میکروبیولوژی پزشکی در پژوهش‌های آزمایشگاهی مشارکت دارد و از فرایندهای تجربی قابل‌اعتماد و مطالعات میان‌رشته‌ای پشتیبانی می‌کند.",
      ar: "تشارك الدكتورة كلالي في البحث المختبري بخلفية في الأحياء والأحياء الدقيقة الطبية وتدعم الدراسات متعددة التخصصات.",
    },
    image: "/media/team/naeemeh-kalali.jpg",
    areas: ["research"],
  },
  {
    slug: "alireza-akbarzadeh",
    name: { en: "Dr. Alireza Akbarzadeh", fa: "دکتر علیرضا اکبرزاده", ar: "الدكتور علي رضا أكبرزاده" },
    role: { en: "Mechanical engineering and innovation collaborator", fa: "همکار مهندسی مکانیک و نوآوری", ar: "متعاون في الهندسة الميكانيكية والابتكار" },
    summary: {
      en: "Brings mechanical engineering, automation, and product-development experience to interdisciplinary projects.",
      fa: "انتقال تجربه مهندسی مکانیک، اتوماسیون و توسعه محصول به پروژه‌های میان‌رشته‌ای.",
      ar: "يوظف خبرة الهندسة الميكانيكية والأتمتة وتطوير المنتجات في المشاريع المشتركة.",
    },
    bio: {
      en: "Dr. Akbarzadeh is a mechanical engineer with experience in research and development, automation, and academic collaboration. He contributes engineering perspective to selected innovation projects.",
      fa: "دکتر اکبرزاده مهندس مکانیک با تجربه تحقیق‌وتوسعه، اتوماسیون و همکاری دانشگاهی است و در پروژه‌های منتخب نوآوری دیدگاه مهندسی ارائه می‌کند.",
      ar: "الدكتور أكبرزاده مهندس ميكانيكي ذو خبرة في البحث والتطوير والأتمتة والتعاون الأكاديمي، ويسهم في مشاريع الابتكار المختارة.",
    },
    image: "/media/team/alireza-akbarzadeh.jpg",
    areas: ["innovation"],
  },
];

export function getTeamMembers(area: TeamArea) {
  return teamMembers.filter((member) => member.areas.includes(area));
}

export function findTeamMember(slug: string) {
  return teamMembers.find((member) => member.slug === slug);
}

export const teamLabels: Record<Locale, {
  kicker: string; title: string; intro: string; readProfile: string; back: string;
  profileIntro: string; expertise: string; collaboration: string;
}> = {
  en: { kicker: "People", title: "Meet the team", intro: "Clinical, research, and engineering work is delivered through coordinated specialist roles.", readProfile: "View profile", back: "Back to the team", profileIntro: "Professional profile", expertise: "Area of work", collaboration: "Contribution to the team" },
  fa: { kicker: "اعضای تیم", title: "آشنایی با تیم", intro: "فعالیت بالینی، پژوهشی و مهندسی با همکاری نقش‌های تخصصی و هماهنگ پیش می‌رود.", readProfile: "مشاهده صفحه عضو", back: "بازگشت به اعضای تیم", profileIntro: "پروفایل حرفه‌ای", expertise: "حوزه فعالیت", collaboration: "نقش در تیم" },
  ar: { kicker: "الفريق", title: "تعرّف إلى الفريق", intro: "تتقدم الأعمال السريرية والبحثية والهندسية عبر أدوار تخصصية منسقة.", readProfile: "عرض الملف", back: "العودة إلى الفريق", profileIntro: "الملف المهني", expertise: "مجال العمل", collaboration: "الدور في الفريق" },
};

export const clinicHubCopy: Record<Locale, {
  pathwaysKicker: string; pathwaysTitle: string; pathwaysIntro: string;
  pathways: Array<{ slug: string; title: string; text: string; image: string }>;
  clinicGalleryTitle: string; clinicGalleryIntro: string;
  hospitalGalleryTitle: string; hospitalGalleryIntro: string;
  viewGallery: string; previous: string; next: string; close: string;
  backToClinic: string;
}> = {
  en: {
    pathwaysKicker: "Care locations", pathwaysTitle: "Choose the right care setting", pathwaysIntro: "Explore outpatient clinic services and hospital-based surgery through two focused pathways.",
    pathways: [
      { slug: "clinical-care/clinic-services", title: "Clinic services", text: "Consultation, selected office procedures, follow-up, and early recovery guidance.", image: "/media/galleries/clinic/clinic-08.jpg" },
      { slug: "clinical-care/hospital-services", title: "Hospital services", text: "Major and subspecialty operations planned for an appropriately equipped hospital.", image: "/media/galleries/hospital/hospital-14.jpg" },
    ],
    clinicGalleryTitle: "Inside the clinic", clinicGalleryIntro: "A selected view of the private-office environment and professional workflow.", hospitalGalleryTitle: "Hospital-based care", hospitalGalleryIntro: "Selected professional images representing the hospital and subspecialty-care pathway.", viewGallery: "View full gallery", previous: "Previous image", next: "Next image", close: "Close gallery", backToClinic: "Back to the main clinic page",
  },
  fa: {
    pathwaysKicker: "محل ارائه خدمات", pathwaysTitle: "مسیر مناسب مراقبت را انتخاب کنید", pathwaysIntro: "خدمات سرپایی کلینیک و جراحی‌های بیمارستانی در دو مسیر مستقل معرفی شده‌اند.",
    pathways: [
      { slug: "clinical-care/clinic-services", title: "خدمات کلینیک", text: "مشاوره، اقدامات منتخب مطب، پیگیری و راهنمای اولیه دوران بهبود.", image: "/media/galleries/clinic/clinic-08.jpg" },
      { slug: "clinical-care/hospital-services", title: "خدمات بیمارستان", text: "جراحی‌های بزرگ و فوق‌تخصصی در بیمارستان مجهز و متناسب با نیاز بیمار.", image: "/media/galleries/hospital/hospital-14.jpg" },
    ],
    clinicGalleryTitle: "فضای کلینیک", clinicGalleryIntro: "روایتی تصویری از فضای مطب خصوصی و روند حرفه‌ای ارائه خدمات.", hospitalGalleryTitle: "خدمات بیمارستانی", hospitalGalleryIntro: "تصاویر منتخب از مسیر حرفه‌ای مراقبت بیمارستانی و فوق‌تخصصی.", viewGallery: "مشاهده گالری کامل", previous: "تصویر قبلی", next: "تصویر بعدی", close: "بستن گالری", backToClinic: "بازگشت به صفحه اصلی کلینیک",
  },
  ar: {
    pathwaysKicker: "مواقع الرعاية", pathwaysTitle: "اختر بيئة الرعاية المناسبة", pathwaysIntro: "تُعرض خدمات العيادة والجراحة في المستشفى ضمن مسارين واضحين.",
    pathways: [
      { slug: "clinical-care/clinic-services", title: "خدمات العيادة", text: "الاستشارة والإجراءات المختارة والمتابعة وإرشادات التعافي الأولية.", image: "/media/galleries/clinic/clinic-08.jpg" },
      { slug: "clinical-care/hospital-services", title: "خدمات المستشفى", text: "العمليات الكبرى والتخصصية في مستشفى مجهز يناسب احتياجات المريض.", image: "/media/galleries/hospital/hospital-14.jpg" },
    ],
    clinicGalleryTitle: "داخل العيادة", clinicGalleryIntro: "مشاهد مختارة من بيئة العيادة الخاصة وسير العمل المهني.", hospitalGalleryTitle: "الرعاية في المستشفى", hospitalGalleryIntro: "صور مختارة لمسار الرعاية التخصصية والمستشفى.", viewGallery: "عرض المعرض الكامل", previous: "الصورة السابقة", next: "الصورة التالية", close: "إغلاق المعرض", backToClinic: "العودة إلى صفحة العيادة الرئيسية",
  },
};

const clinicImages = Array.from({ length: 16 }, (_, index) => `/media/galleries/clinic/clinic-${String(index + 1).padStart(2, "0")}.jpg`);
const hospitalImages = Array.from({ length: 16 }, (_, index) => `/media/galleries/hospital/hospital-${String(index + 1).padStart(2, "0")}.jpg`);

export const galleryCollections = {
  "clinical-care/clinic-gallery": { area: "clinic" as const, images: clinicImages },
  "clinical-care/hospital-gallery": { area: "hospital" as const, images: hospitalImages },
};

export type GalleryRoute = keyof typeof galleryCollections;

const pages = (locale: Locale): Record<string, InteriorPageData> => {
  const isFa = locale === "fa";
  const isAr = locale === "ar";
  const clinic = isFa ? {
    kicker: "خدمات کلینیک", title: "مراقبت تخصصی در محیط مطب.", intro: "ارزیابی، اقدامات منتخب سرپایی، پیگیری و راهنمای دوران بهبود در یک مسیر متمرکز.",
    sections: [
      { title: "مشاوره و ارزیابی", text: "شرح حال، معاینه، بررسی تصاویر و تعریف مسیر درمان متناسب با شرایط هر بیمار." },
      { title: "اقدامات منتخب مطب", text: "برخی اقدامات محدود، تزریق‌ها، مراقبت زخم، گچ و آتل در صورت مناسب‌بودن شرایط بالینی انجام می‌شوند." },
      { title: "پیگیری و توان‌بخشی", text: "پایش روند بهبود و راهنمای اولیه حرکت، محافظت و زمان شروع توان‌بخشی بخشی از مسیر مراقبت است." },
    ], ctaTitle: "برای ویزیت برنامه‌ریزی کنید", ctaText: "نوبت حضوری فقط از مسیر تأییدشده Nobat.ir ثبت می‌شود.",
  } : isAr ? {
    kicker: "خدمات العيادة", title: "رعاية تخصصية في العيادة.", intro: "التقييم والإجراءات الخارجية المختارة والمتابعة وإرشادات التعافي ضمن مسار مركز.",
    sections: [
      { title: "الاستشارة والتقييم", text: "التاريخ والفحص ومراجعة الصور وتحديد مسار يناسب حالة كل مريض." },
      { title: "إجراءات مختارة", text: "تُجرى بعض الإجراءات المحدودة والحقن والعناية بالجروح والجبائر عندما تكون مناسبة سريرياً." },
      { title: "المتابعة والتأهيل", text: "تدعم متابعة التعافي وإرشادات الحركة والحماية وتوقيت التأهيل استعادة الوظيفة الآمنة." },
    ], ctaTitle: "خطط لزيارتك", ctaText: "تُحجز الزيارة الحضورية فقط عبر مسار Nobat.ir المعتمد.",
  } : {
    kicker: "Clinic services", title: "Specialist care in the private office.", intro: "Assessment, selected outpatient procedures, follow-up, and recovery guidance in one focused pathway.",
    sections: [
      { title: "Consultation and assessment", text: "History, examination, imaging review, and a care pathway shaped around the individual condition." },
      { title: "Selected office procedures", text: "Suitable minor procedures, injections, wound care, casting, and splinting may be provided when clinically appropriate." },
      { title: "Follow-up and rehabilitation", text: "Recovery monitoring and early guidance on movement, protection, and rehabilitation support a safe return of function." },
    ], ctaTitle: "Plan a specialist visit", ctaText: "Planned in-person appointments use the approved Nobat.ir pathway.",
  };

  const hospital = isFa ? {
    kicker: "خدمات بیمارستان", title: "جراحی‌های بزرگ در مرکز مجهز.", intro: "خدمات بیمارستانی زمانی انتخاب می‌شوند که ایمنی، بیهوشی، تجهیزات یا مراقبت پس از عمل به امکانات تخصصی نیاز داشته باشد.",
    sections: [
      { title: "جراحی‌های فوق‌تخصصی", text: "بازسازی‌های پیچیده، میکروسرجری و جراحی‌های وابسته به تجهیزات در محیط بیمارستانی برنامه‌ریزی می‌شوند." },
      { title: "انتخاب محل درمان", text: "محل انجام جراحی پس از ارزیابی تشخیص، سطح پیچیدگی و نیازهای ایمنی مشخص می‌شود." },
      { title: "هماهنگی قبل و بعد از عمل", text: "مدارک، آمادگی، پیگیری و توان‌بخشی باید طبق دستور اختصاصی تیم درمان انجام شوند." },
    ], ctaTitle: "مسیر جراحی را با تیم درمان هماهنگ کنید", ctaText: "ارزیابی بالینی تعیین می‌کند کدام مرکز برای جراحی مناسب است.",
  } : isAr ? {
    kicker: "خدمات المستشفى", title: "الجراحة الكبرى في مركز مجهز.", intro: "تُختار الرعاية في المستشفى عندما تتطلب السلامة أو التخدير أو التجهيزات أو المتابعة مرافق تخصصية.",
    sections: [
      { title: "الجراحة التخصصية", text: "تُخطط إعادة البناء المعقدة والجراحة المجهرية والعمليات المعتمدة على تجهيزات في بيئة المستشفى." },
      { title: "اختيار مكان العلاج", text: "يُحدد المركز بعد تقييم التشخيص والتعقيد ومتطلبات السلامة." },
      { title: "التنسيق قبل الجراحة وبعدها", text: "يجب اتباع تعليمات الفريق الفردية للاستعداد والمتابعة والتأهيل." },
    ], ctaTitle: "نسق مسار الجراحة مع الفريق", ctaText: "يحدد التقييم السريري المركز المناسب للجراحة.",
  } : {
    kicker: "Hospital services", title: "Major surgery in an equipped center.", intro: "Hospital care is selected when safety, anesthesia, equipment, or postoperative needs require specialist facilities.",
    sections: [
      { title: "Subspecialty surgery", text: "Complex reconstruction, microsurgery, and equipment-dependent operations are planned in a hospital environment." },
      { title: "Selecting the care location", text: "The center is selected after assessment of the diagnosis, complexity, and safety requirements." },
      { title: "Before-and-after coordination", text: "Preparation, documentation, follow-up, and rehabilitation should follow the treating team’s individual instructions." },
    ], ctaTitle: "Coordinate the surgical pathway", ctaText: "Clinical assessment determines the appropriate hospital setting.",
  };

  const before = isFa ? {
    kicker: "منابع بیمار", title: "راهنمای پیش از جراحی.", intro: "آمادگی عمومی برای روز عمل؛ دستور اختصاصی جراح و بیمارستان همیشه اولویت دارد.", sections: [
      { title: "مدارک و اطلاعات", text: "تصاویر، گزارش‌ها، فهرست داروها، حساسیت‌ها و سوابق درمانی مرتبط را آماده کنید." },
      { title: "دارو و ناشتا بودن", text: "هیچ دارویی را خودسرانه قطع نکنید. زمان ناشتا بودن و تغییر داروها را فقط از تیم درمان بپرسید." },
      { title: "برنامه بازگشت", text: "برای همراه، حمل‌ونقل، مراقبت در منزل و محدودیت کار یا رانندگی از قبل برنامه‌ریزی کنید." },
    ], ctaTitle: "دستور اختصاصی خود را تأیید کنید", ctaText: "در صورت ابهام، پیش از روز عمل با تیم درمان تماس بگیرید.",
  } : isAr ? {
    kicker: "موارد المريض", title: "دليل ما قبل الجراحة.", intro: "إعداد عام ليوم العملية؛ تبقى تعليمات الجراح والمستشفى الفردية هي الأولوية.", sections: [
      { title: "الوثائق والمعلومات", text: "جهز الصور والتقارير وقائمة الأدوية والحساسيات والتاريخ الطبي المرتبط." },
      { title: "الأدوية والصيام", text: "لا توقف دواءً بنفسك؛ أكد الصيام وأي تعديل دوائي مع الفريق المعالج." },
      { title: "خطة العودة", text: "رتب المرافق والنقل والرعاية المنزلية وحدود العمل أو القيادة مسبقاً." },
    ], ctaTitle: "أكد تعليماتك الفردية", ctaText: "تواصل مع الفريق قبل يوم العملية عند وجود أي غموض.",
  } : {
    kicker: "Patient resources", title: "Before-surgery guidance.", intro: "General preparation for the day of surgery; individual surgeon and hospital instructions always take priority.", sections: [
      { title: "Documents and information", text: "Prepare relevant imaging, reports, medication and allergy lists, and previous treatment information." },
      { title: "Medication and fasting", text: "Do not stop medication on your own. Confirm fasting and medication changes only with the treating team." },
      { title: "Planning the return home", text: "Arrange an escort, transport, home support, and expected limits on work or driving in advance." },
    ], ctaTitle: "Confirm your individual instructions", ctaText: "Contact the treating team before surgery if any instruction is unclear.",
  };

  const after = isFa ? {
    kicker: "منابع بیمار", title: "مراقبت پس از جراحی.", intro: "راهنمای عمومی برای محافظت و تشخیص علائم هشدار؛ دستور اختصاصی تیم درمان مقدم است.", sections: [
      { title: "پانسمان و محافظت", text: "پانسمان و آتل را طبق دستور خشک و ثابت نگه دارید و بدون هماهنگی آن را باز نکنید." },
      { title: "درد، تورم و حرکت", text: "داروها، بالا نگه‌داشتن اندام و تمرین‌های مجاز را دقیقاً طبق برنامه انجام دهید." },
      { title: "علائم هشدار", text: "درد کنترل‌نشده، تغییر رنگ، بی‌حسی رو به افزایش، خونریزی، تب یا تورم سریع به ارزیابی فوری نیاز دارد." },
    ], ctaTitle: "برنامه پیگیری را حفظ کنید", ctaText: "زمان ویزیت، تعویض پانسمان و شروع توان‌بخشی را با تیم درمان تأیید کنید.",
  } : isAr ? {
    kicker: "موارد المريض", title: "العناية بعد الجراحة.", intro: "إرشاد عام للحماية والتعرف إلى علامات التحذير؛ تعليمات الفريق الفردية أولاً.", sections: [
      { title: "الضماد والحماية", text: "حافظ على الضماد والجبيرة جافين وثابتين ولا تفتحهما دون توجيه." },
      { title: "الألم والتورم والحركة", text: "اتبع الأدوية ورفع الطرف والتمارين المسموحة وفق الخطة المحددة." },
      { title: "علامات التحذير", text: "الألم غير المضبوط أو تغير اللون أو الخدر المتزايد أو النزف أو الحمى أو التورم السريع يحتاج إلى تقييم عاجل." },
    ], ctaTitle: "حافظ على خطة المتابعة", ctaText: "أكد موعد الزيارة وتغيير الضماد وبدء التأهيل مع الفريق.",
  } : {
    kicker: "Patient resources", title: "After-surgery care.", intro: "General protection and warning-sign guidance; the treating team’s individual instructions come first.", sections: [
      { title: "Dressings and protection", text: "Keep dressings and splints dry and secure as instructed, and do not remove them without guidance." },
      { title: "Pain, swelling, and movement", text: "Follow the prescribed medication, elevation, and permitted movement plan exactly." },
      { title: "Warning signs", text: "Uncontrolled pain, color change, increasing numbness, bleeding, fever, or rapidly increasing swelling needs prompt assessment." },
    ], ctaTitle: "Keep the follow-up plan", ctaText: "Confirm the visit, dressing, and rehabilitation schedule with the treating team.",
  };

  const faq = isFa ? {
    kicker: "منابع بیمار", title: "پرسش‌های متداول.", intro: "پاسخ‌های عمومی برای برنامه‌ریزی مراجعه و درمان.", sections: [
      { title: "چگونه نوبت بگیرم؟", text: "ویزیت برنامه‌ریزی‌شده فقط از پروفایل تأییدشده Nobat.ir ثبت می‌شود." },
      { title: "چه مدارکی همراه داشته باشم؟", text: "تصاویر، گزارش‌ها، فهرست داروها و اطلاعات عمل یا درمان قبلی را همراه بیاورید." },
      { title: "آیا همه اقدامات در مطب انجام می‌شوند؟", text: "خیر؛ اقدامات سرپایی مناسب در مطب و جراحی‌های بزرگ در بیمارستان مجهز انجام می‌شوند." },
      { title: "آسیب فوری چه می‌شود؟", text: "موارد تهدیدکننده جان یا اندام باید فوراً به اورژانس مراجعه کنند و منتظر نوبت معمول نمانند." },
    ], ctaTitle: "پاسخ اختصاصی نیازمند ارزیابی است", ctaText: "این راهنما جایگزین معاینه یا دستور پزشکی فردی نیست.",
  } : isAr ? {
    kicker: "موارد المريض", title: "الأسئلة الشائعة.", intro: "إجابات عامة لتخطيط الزيارة والعلاج.", sections: [
      { title: "كيف أحجز موعداً؟", text: "تُحجز الزيارة المخططة فقط عبر ملف Nobat.ir المعتمد." },
      { title: "ماذا أحضر؟", text: "أحضر الصور والتقارير وقائمة الأدوية ومعلومات العمليات أو العلاجات السابقة." },
      { title: "هل تُجرى كل الإجراءات في العيادة؟", text: "لا؛ الإجراءات الخارجية المناسبة في العيادة والجراحة الكبرى في مستشفى مجهز." },
      { title: "ماذا عن الإصابة العاجلة؟", text: "الحالات المهددة للحياة أو الطرف تحتاج إلى الطوارئ فوراً ولا تنتظر موعداً روتينياً." },
    ], ctaTitle: "الإجابة الفردية تحتاج إلى تقييم", ctaText: "هذا الدليل لا يستبدل الفحص أو التعليمات الطبية الفردية.",
  } : {
    kicker: "Patient resources", title: "Frequently asked questions.", intro: "General answers for planning a visit and treatment.", sections: [
      { title: "How do I book?", text: "Planned visits are booked only through the approved Nobat.ir profile." },
      { title: "What should I bring?", text: "Bring imaging, reports, medication lists, and information about previous operations or treatment." },
      { title: "Are all procedures performed in the office?", text: "No. Suitable outpatient procedures use the clinic; major surgery uses an equipped hospital." },
      { title: "What about an urgent injury?", text: "Life- or limb-threatening injuries require emergency services and must not wait for routine booking." },
    ], ctaTitle: "Individual answers require assessment", ctaText: "This guidance does not replace examination or individual medical instructions.",
  };

  const rehab = isFa ? {
    kicker: "منابع بیمار", title: "راهنمای توان‌بخشی.", intro: "حرکت باید در زمان مناسب، با دامنه مجاز و متناسب با بافت ترمیم‌شده آغاز شود.", sections: [
      { title: "محافظت و حرکت", text: "تعادل میان محافظت و حرکت به نوع آسیب، روش درمان و مرحله ترمیم بستگی دارد." },
      { title: "هندتراپی", text: "در صورت نیاز، درمانگر دست روی حرکت، ورم، اسکار، قدرت و بازگشت به فعالیت کار می‌کند." },
      { title: "تمرین خانگی", text: "فقط تمرین‌های تجویزشده را با تعداد و دامنه مشخص انجام دهید و در صورت افزایش غیرعادی درد متوقف کنید." },
    ], ctaTitle: "برنامه توان‌بخشی اختصاصی است", ctaText: "زمان شروع و پیشرفت تمرین را تیم درمان مشخص می‌کند.",
  } : isAr ? {
    kicker: "موارد المريض", title: "إرشادات التأهيل.", intro: "يبدأ التحريك في الوقت والمدى المناسبين بحسب النسيج المعالج.", sections: [
      { title: "الحماية والحركة", text: "يعتمد التوازن بين الحماية والحركة على الإصابة والعلاج ومرحلة الالتئام." },
      { title: "علاج اليد", text: "عند الحاجة يعمل معالج اليد على الحركة والتورم والندبة والقوة والعودة إلى النشاط." },
      { title: "التمرين المنزلي", text: "نفذ التمارين الموصوفة فقط وتوقف عند زيادة غير معتادة في الألم." },
    ], ctaTitle: "خطة التأهيل فردية", ctaText: "يحدد الفريق توقيت بدء التمرين وتقدمه.",
  } : {
    kicker: "Patient resources", title: "Rehabilitation guidance.", intro: "Movement should begin at the right time and range for the tissue being treated.", sections: [
      { title: "Protection and movement", text: "The balance between protection and motion depends on the injury, treatment, and healing stage." },
      { title: "Hand therapy", text: "When needed, a hand therapist addresses motion, swelling, scar, strength, and return to activity." },
      { title: "Home exercises", text: "Perform only prescribed exercises at the stated dose and range, and stop for an unusual increase in pain." },
    ], ctaTitle: "Rehabilitation is individualized", ctaText: "The treating team sets the timing and progression of exercise.",
  };

  return {
    "clinical-care/clinic-services": clinic,
    "clinical-care/hospital-services": hospital,
    "patient-resources/before-surgery": before,
    "patient-resources/after-surgery": after,
    "patient-resources/faq": faq,
    "patient-resources/rehabilitation": rehab,
  };
};

export const supplementalPages: Record<Locale, Record<string, InteriorPageData>> = {
  en: pages("en"), fa: pages("fa"), ar: pages("ar"),
};

export const supplementalPageRoutes = Object.keys(supplementalPages.en);

export const supplementalCoverImages: Record<string, string> = {
  "clinical-care/clinic-services": "/media/galleries/clinic/clinic-08.jpg",
  "clinical-care/hospital-services": "/media/galleries/hospital/hospital-14.jpg",
  "patient-resources/before-surgery": "/media/appointments/doctor.jpg",
  "patient-resources/after-surgery": "/media/connected-practice/04-life.jpg",
  "patient-resources/faq": "/media/pages/clinic-cover.jpg",
  "patient-resources/rehabilitation": "/media/connected-practice/04-life.jpg",
  "clinical-care/clinic-gallery": "/media/galleries/clinic/clinic-08.jpg",
  "clinical-care/hospital-gallery": "/media/galleries/hospital/hospital-14.jpg",
};
