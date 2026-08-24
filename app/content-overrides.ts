import type { InteriorPageData, Locale, SectionLink } from "./site-content";
import { aboutPageCopy } from "./about-content";

type InnovationRecord = {
  title: string;
  text: string;
  links: SectionLink[];
};

const sourceLink = (label: string, href: string): SectionLink => ({ label, href, external: true });
const internalLink = (label: string, href: string): SectionLink => ({ label, href });

const innovationRecords: Record<Locale, InnovationRecord[]> = {
  en: [
    { title: "Dynamometer", text: "A force-monitoring accessory developed for dynamic distal-radius external fixation, intended to help the surgical team check distraction force during application and follow-up.", links: [internalLink("Read the project page", "innovation/dynamometer")] },
    { title: "Magnetic joint distraction", text: "A research concept for continuous magnetic distraction across the first carpometacarpal joint, developed as an implant-based approach to joint unloading.", links: [internalLink("Read the project page", "innovation/magnetic-joint-distraction")] },
    { title: "Dynamic distal-radius external fixator", text: "A low-profile external-fixation concept with adjustable clamps and gradual distraction, designed to orient traction relative to the distal-radius articular surface.", links: [internalLink("Read the project page", "innovation/dynamic-distal-radius-external-fixator")] },
    { title: "Dynamic hip external fixator", text: "An external-fixation product pathway for dynamic management around the hip, documented by the Avisa medical-device catalogue.", links: [sourceLink("Open the product reference", "https://avisa-med.com/index.php/products/external-fixators/dynamic-hip-external-fixator")] },
    { title: "Intra-osseous DRUJ prosthesis", text: "A high-technology development concept for an intra-osseous prosthetic solution at the distal radioulnar joint.", links: [sourceLink("Open the product reference", "https://avisa-med.com/index.php/products/high-technological-in-process-products/intra-osseous-distal-radioulnar-prosthesis")] },
    { title: "Lag plate", text: "A plate-development concept intended to combine lag-screw mechanics with a dedicated fixation construct.", links: [sourceLink("Open the product reference", "https://avisa-med.com/index.php/products/high-technological-in-process-products/lag-plate")] },
    { title: "Artificial finger pulley", text: "A reconstructive-device concept for replacing or supporting the finger pulley mechanism during tendon reconstruction.", links: [sourceLink("Open the product reference", "https://avisa-med.com/index.php/products/high-technological-in-process-products/artificial-finger-pulley")] },
    { title: "Bionic Hand H3", text: "A myoelectrically controlled prosthetic hand for upper-limb absence, developed around fast response, proportional control, grip sensing, and everyday usability.", links: [internalLink("Read the project page", "innovation/bionic-hand-h3"), sourceLink("Open the product reference", "https://avisa-med.com/index.php/products/bionic-hand/integlim-hand")] },
    { title: "Bionic-hand training software", text: "A software pathway for learning, configuring, and practising control of the Integrom bionic-hand system.", links: [sourceLink("Open the software reference", "https://avisa-med.com/index.php/products/bionic-hand/integlearn-software")] },
    { title: "Bionic Hand H5", text: "A multi-grip prosthetic hand with five independently powered fingers, configurable control modes, and mechanisms intended for responsive daily use.", links: [internalLink("Read the project page", "innovation/bionic-hand-h5")] },
    { title: "Magnetic control for an artificial limb", text: "A research programme investigating magnetic sensing and control interfaces for more intuitive activation of an artificial upper limb.", links: [sourceLink("Open the research reference", "https://orthopresearch.com/index.php/innovative-projects-inventions/robotics/bionic-limb")] },
    { title: "Integrated stem", text: "A segmented intramedullary radius stem concept developed to address the narrow diameter, curvature, and paired-bone mechanics of transradial osseointegration.", links: [internalLink("Read the project page", "innovation/integrated-stem")] },
    { title: "Hip Exoskeleton HEXA", text: "A wearable robotic hip system designed to support gait rehabilitation by assisting hip flexion and improving selected walking parameters.", links: [internalLink("Read the project page", "innovation/hip-exoskeleton-hexa")] },
    { title: "Coated Schanz pins", text: "A product family of Schanz pins and fixation pins with surface-coating options for external-fixation applications.", links: [sourceLink("Open the product reference", "http://avisa-med.com/index.php/products/schanz-pins")] },
  ],
  fa: [
    { title: "دینامومتر", text: "ابزار پایش نیرو برای فیکساتور خارجی دینامیک دیستال رادیوس که برای کنترل نیروی دیسترکشن هنگام کارگذاری و پیگیری بیمار توسعه یافته است.", links: [internalLink("مشاهده صفحه پروژه", "innovation/dynamometer")] },
    { title: "دیسترکشن مغناطیسی مفصل", text: "مفهوم پژوهشی ایجاد دیسترکشن پیوسته و مغناطیسی در مفصل CMC1 با رویکرد ایمپلنتی برای کاهش بار مفصل.", links: [internalLink("مشاهده صفحه پروژه", "innovation/magnetic-joint-distraction")] },
    { title: "فیکساتور خارجی دینامیک دیستال رادیوس", text: "سامانه فیکساتور کم‌حجم با کلمپ‌های قابل‌تنظیم و دیسترکشن تدریجی برای هدایت نیروی کشش نسبت به سطح مفصلی دیستال رادیوس.", links: [internalLink("مشاهده صفحه پروژه", "innovation/dynamic-distal-radius-external-fixator")] },
    { title: "فیکساتور خارجی دینامیک هیپ", text: "مسیر محصول فیکساتور خارجی برای مدیریت دینامیک ناحیه هیپ که در کاتالوگ تجهیزات پزشکی آویسا معرفی شده است.", links: [sourceLink("مشاهده مرجع محصول", "https://avisa-med.com/index.php/products/external-fixators/dynamic-hip-external-fixator")] },
    { title: "پروتز داخل‌استخوانی DRUJ", text: "مفهوم محصول فناورانه برای راهکار پروتزی داخل‌استخوانی در مفصل رادیواولنار دیستال.", links: [sourceLink("مشاهده مرجع محصول", "https://avisa-med.com/index.php/products/high-technological-in-process-products/intra-osseous-distal-radioulnar-prosthesis")] },
    { title: "پلاک Lag", text: "مفهوم توسعه پلاک با هدف ترکیب مکانیک پیچ Lag و یک سازه اختصاصی تثبیت.", links: [sourceLink("مشاهده مرجع محصول", "https://avisa-med.com/index.php/products/high-technological-in-process-products/lag-plate")] },
    { title: "پولی مصنوعی انگشت", text: "مفهوم وسیله بازسازی برای جایگزینی یا پشتیبانی سازوکار پولی انگشت در بازسازی تاندون.", links: [sourceLink("مشاهده مرجع محصول", "https://avisa-med.com/index.php/products/high-technological-in-process-products/artificial-finger-pulley")] },
    { title: "دست بیونیک H3", text: "دست پروتزی با کنترل مایوالکتریک برای فقدان اندام فوقانی با تمرکز بر پاسخ سریع، کنترل تناسبی، حس نیروی گرفتن و استفاده روزمره.", links: [internalLink("مشاهده صفحه پروژه", "innovation/bionic-hand-h3"), sourceLink("مشاهده مرجع محصول", "https://avisa-med.com/index.php/products/bionic-hand/integlim-hand")] },
    { title: "نرم‌افزار آموزش دست بیونیک", text: "مسیر نرم‌افزاری برای یادگیری، تنظیم و تمرین کنترل سامانه دست بیونیک Integrom.", links: [sourceLink("مشاهده مرجع نرم‌افزار", "https://avisa-med.com/index.php/products/bionic-hand/integlearn-software")] },
    { title: "دست بیونیک H5", text: "دست پروتزی چندحالته با پنج انگشت دارای محرک مستقل، حالت‌های کنترل قابل‌تنظیم و سازوکارهای مناسب استفاده روزانه.", links: [internalLink("مشاهده صفحه پروژه", "innovation/bionic-hand-h5")] },
    { title: "کنترل مغناطیسی اندام مصنوعی", text: "برنامه پژوهشی برای بررسی حسگرهای مغناطیسی و رابط‌های کنترلی با هدف فعال‌سازی طبیعی‌تر اندام مصنوعی فوقانی.", links: [sourceLink("مشاهده مرجع پژوهشی", "https://orthopresearch.com/index.php/innovative-projects-inventions/robotics/bionic-limb")] },
    { title: "استم یکپارچه", text: "مفهوم استم داخل‌استخوانی قطعه‌ای رادیوس برای پاسخ به قطر کم، انحنای استخوان و مکانیک دو استخوان ساعد در اوسئواینتگریشن ترانس‌رادیال.", links: [internalLink("مشاهده صفحه پروژه", "innovation/integrated-stem")] },
    { title: "اسکلت بیرونی هیپ HEXA", text: "سامانه رباتیک پوشیدنی برای پشتیبانی توان‌بخشی راه‌رفتن با کمک به فلکشن هیپ و بهبود برخی شاخص‌های گام.", links: [internalLink("مشاهده صفحه پروژه", "innovation/hip-exoskeleton-hexa")] },
    { title: "پین‌ها و شانزهای پوشش‌دار", text: "خانواده‌ای از پین‌ها و شانزهای فیکساتور با گزینه‌های پوشش سطحی برای کاربردهای فیکساتور خارجی.", links: [sourceLink("مشاهده مرجع محصول", "http://avisa-med.com/index.php/products/schanz-pins")] },
  ],
  ar: [
    { title: "مقياس قوة الشد", text: "أداة لمراقبة القوة مع المثبت الخارجي الديناميكي للكعبرة البعيدة، طُورت للتحقق من قوة الإبعاد أثناء التطبيق والمتابعة.", links: [internalLink("عرض صفحة المشروع", "innovation/dynamometer")] },
    { title: "إبعاد المفصل مغناطيسياً", text: "مفهوم بحثي لإحداث إبعاد مغناطيسي مستمر عبر المفصل الرسغي المشطي الأول بهدف تخفيف الحمل على المفصل.", links: [internalLink("عرض صفحة المشروع", "innovation/magnetic-joint-distraction")] },
    { title: "المثبت الخارجي الديناميكي للكعبرة البعيدة", text: "مفهوم منخفض البروز بمشابك قابلة للضبط وإبعاد تدريجي لتوجيه قوة الشد بالنسبة إلى السطح المفصلي للكعبرة البعيدة.", links: [internalLink("عرض صفحة المشروع", "innovation/dynamic-distal-radius-external-fixator")] },
    { title: "المثبت الخارجي الديناميكي للورك", text: "مسار منتج للتثبيت الخارجي الديناميكي حول الورك موثق في كتالوج أجهزة Avisa الطبية.", links: [sourceLink("فتح مرجع المنتج", "https://avisa-med.com/index.php/products/external-fixators/dynamic-hip-external-fixator")] },
    { title: "بدلة داخل العظم للمفصل الكعبري الزندي البعيد", text: "مفهوم تقني لبدلة داخل العظم في المفصل الكعبري الزندي البعيد.", links: [sourceLink("فتح مرجع المنتج", "https://avisa-med.com/index.php/products/high-technological-in-process-products/intra-osseous-distal-radioulnar-prosthesis")] },
    { title: "صفيحة Lag", text: "مفهوم يجمع ميكانيكا برغي Lag مع بنية تثبيت مخصصة.", links: [sourceLink("فتح مرجع المنتج", "https://avisa-med.com/index.php/products/high-technological-in-process-products/lag-plate")] },
    { title: "بكرة إصبع صناعية", text: "مفهوم جهاز ترميمي لاستبدال آلية بكرة الإصبع أو دعمها أثناء ترميم الوتر.", links: [sourceLink("فتح مرجع المنتج", "https://avisa-med.com/index.php/products/high-technological-in-process-products/artificial-finger-pulley")] },
    { title: "اليد الإلكترونية H3", text: "يد تعويضية بتحكم عضلي كهربائي صُممت للاستجابة السريعة والتحكم التناسبي واستشعار القبضة والاستخدام اليومي.", links: [internalLink("عرض صفحة المشروع", "innovation/bionic-hand-h3"), sourceLink("فتح مرجع المنتج", "https://avisa-med.com/index.php/products/bionic-hand/integlim-hand")] },
    { title: "برنامج تدريب اليد الإلكترونية", text: "مسار برمجي لتعلم نظام اليد الإلكترونية Integrom وإعداده والتدرب على التحكم به.", links: [sourceLink("فتح مرجع البرنامج", "https://avisa-med.com/index.php/products/bionic-hand/integlearn-software")] },
    { title: "اليد الإلكترونية H5", text: "يد تعويضية متعددة القبضات بخمسة أصابع ذات محركات مستقلة وأنماط تحكم قابلة للتهيئة للاستخدام اليومي.", links: [internalLink("عرض صفحة المشروع", "innovation/bionic-hand-h5")] },
    { title: "التحكم المغناطيسي بالطرف الصناعي", text: "برنامج بحثي في الاستشعار المغناطيسي وواجهات التحكم لتفعيل الطرف العلوي الصناعي بصورة أكثر بديهية.", links: [sourceLink("فتح المرجع البحثي", "https://orthopresearch.com/index.php/innovative-projects-inventions/robotics/bionic-limb")] },
    { title: "الساق المتكاملة", text: "مفهوم لساق كعبرية نخاعية مجزأة لمعالجة ضيق العظم وانحنائه وميكانيكا عظمي الساعد في الاندماج العظمي عبر الكعبرة.", links: [internalLink("عرض صفحة المشروع", "innovation/integrated-stem")] },
    { title: "الهيكل الخارجي للورك HEXA", text: "نظام روبوتي قابل للارتداء لدعم تأهيل المشي عبر مساعدة ثني الورك وتحسين مؤشرات مختارة للخطوة.", links: [internalLink("عرض صفحة المشروع", "innovation/hip-exoskeleton-hexa")] },
    { title: "مسامير Schanz المطلية", text: "عائلة من مسامير التثبيت مع خيارات طلاء سطحي لاستخدامات التثبيت الخارجي.", links: [sourceLink("فتح مرجع المنتج", "http://avisa-med.com/index.php/products/schanz-pins")] },
  ],
};

function innovationPage(locale: Locale): InteriorPageData {
  const copy = locale === "fa"
    ? { kicker: "نوآوری", title: "چهارده مسیر نوآوری بالینی و مهندسی.", intro: "فهرست تأییدشده پروژه‌ها و محصولات از فایل Innovation-list؛ هر رکورد به صفحه پروژه یا مرجع اصلی آن متصل است.", ctaTitle: "از مسئله بالینی تا راهکار قابل‌ارزیابی", ctaText: "هر پروژه در مرحله‌ای متفاوت از پژوهش، توسعه محصول یا انتقال فناوری قرار دارد." }
    : locale === "ar"
      ? { kicker: "الابتكار", title: "أربعة عشر مساراً للابتكار السريري والهندسي.", intro: "القائمة المعتمدة من ملف Innovation-list؛ يرتبط كل سجل بصفحة المشروع أو مرجعه الأصلي.", ctaTitle: "من المشكلة السريرية إلى حل قابل للتقييم", ctaText: "يقع كل مشروع في مرحلة مختلفة من البحث أو تطوير المنتج أو نقل التقنية." }
      : { kicker: "Innovation", title: "Fourteen clinical and engineering innovation pathways.", intro: "The verified Innovation-list records, each connected to a dedicated project page or its original reference.", ctaTitle: "From clinical problem to testable solution", ctaText: "Each project sits at a different stage of research, product development, or technology transfer." };
  return { ...copy, sections: innovationRecords[locale] };
}

const detailPages: Record<Locale, Record<string, InteriorPageData>> = {
  en: {
    "innovation/dynamometer": { kicker: "Innovation project", title: "Dynamometer for controlled distraction.", intro: "Monitoring distraction force during dynamic distal-radius external fixation.", sections: [
      { title: "Clinical problem", text: "During closed treatment of distal-radius fractures, excessive traction may increase tension on the tendons—particularly the extensor tendons—and contribute to stiffness or reduced finger motion." },
      { title: "Concept", text: "The dynamometer is used with the dynamic distal-radius external fixator so the treating team can check the applied distraction force during surgery and at later follow-up visits." },
      { title: "Two reading systems", text: "The archived design describes a quantitative scale for direct force measurement and a colour scale with green, yellow, and red zones to indicate increasing force levels." },
      { title: "Archived source", text: "This page restructures the approved content from Dr. Moradi’s previous website.", links: [sourceLink("Open the original page", "https://dralimoradi.com/external-fixators-1/")] },
    ], ctaTitle: "Force should be adjusted by the treating team", ctaText: "Device use and distraction settings require clinical judgment and do not constitute patient instructions." },
    "innovation/magnetic-joint-distraction": { kicker: "Innovation project", title: "Magnetic joint distraction.", intro: "An implant-based research concept for sustained distraction at the thumb CMC joint.", sections: [
      { title: "Rationale", text: "Joint distraction is investigated as a way to unload an arthritic joint and potentially reduce pain. The archived project focuses on the first carpometacarpal joint." },
      { title: "Implant concept", text: "The design proposes a pair of implants, each containing a magnet within a neutral capsule, positioned in the trapezium and first metacarpal." },
      { title: "Proposed mechanism", text: "Like magnetic poles face one another so that a continuous repulsive force creates distraction across the joint. The concept remains a research and development pathway rather than general treatment guidance." },
      { title: "Archived source", text: "This page restructures the approved content from Dr. Moradi’s previous website.", links: [sourceLink("Open the original page", "https://dralimoradi.com/magnetic-distractor/")] },
    ], ctaTitle: "A research concept under evaluation", ctaText: "Clinical suitability, safety, and evidence must be established through appropriate research." },
    "innovation/dynamic-distal-radius-external-fixator": { kicker: "Innovation project", title: "Dynamic distal-radius external fixator.", intro: "Adjustable external fixation designed around gradual distraction and the articular traction axis.", sections: [
      { title: "External fixation", text: "External fixation uses percutaneous pins connected to an external frame to stabilize bone while preserving access to injured soft tissue. It may be considered in selected traumatic and reconstructive settings." },
      { title: "Design features", text: "The archived device is described as low profile and lightweight, with adjustable clamps, flexible Schanz-pin placement, and a distractor nut for gradual distraction." },
      { title: "Biomechanical concept", text: "Adjustable joints allow the traction axis to be oriented relative to the distal-radius articular surface, supporting ligamentotaxis while the treating surgeon checks alignment and force." },
      { title: "Archived source", text: "This page restructures the approved content from Dr. Moradi’s previous website.", links: [sourceLink("Open the original page", "https://dralimoradi.com/external-fixators/")] },
    ], ctaTitle: "Device selection is case-specific", ctaText: "External fixation requires specialist assessment, imaging, and an individualized operative plan." },
    "innovation/bionic-hand-h3": { kicker: "Innovation project", title: "Bionic Hand H3.", intro: "A myoelectrically controlled prosthetic hand designed for responsive everyday grasp.", sections: [
      { title: "Intended users", text: "The archived product description presents the H3 for adults and young or mature users with upper-limb absence, including trans-radial and more proximal levels and unilateral or bilateral use." },
      { title: "Control and performance", text: "The H3 combines EMG-based control with proportional speed, slip detection, grip-force feedback, a non-back-drive mechanism, and a safety-release feature." },
      { title: "Sizing and protection", text: "The product description lists three sizes, wrist compatibility options, a protective PVC shell, and a cosmetic glove intended to protect the mechanism and provide a natural appearance." },
      { title: "References", text: "This page restructures the archived project description and preserves the product reference supplied in the Innovation list.", links: [sourceLink("Open the original page", "https://dralimoradi.com/bionic-hand-h3/"), sourceLink("Open the product reference", "https://avisa-med.com/index.php/products/bionic-hand/integlim-hand")] },
    ], ctaTitle: "Prosthetic fitting is individualized", ctaText: "Selection, fitting, control training, and maintenance require a qualified prosthetic-care team." },
    "innovation/bionic-hand-h5": { kicker: "Innovation project", title: "Bionic Hand H5.", intro: "A multi-grip prosthetic hand with independently powered fingers and configurable control.", sections: [
      { title: "Design", text: "The archived H5 description presents five independently powered fingers, a manually adjustable thumb, five motors, and wrist movement for a total of seven degrees of freedom." },
      { title: "Control", text: "The system is described as supporting EMG- or KMG-based control, independent finger movement, proportional response, multiple grip modes, and force feedback." },
      { title: "Mechanical safeguards", text: "The product concept includes non-back-drivable motion, collision-force absorption, slip detection, and a safety-release mechanism for daily tasks." },
      { title: "Archived source", text: "This page restructures the approved content from Dr. Moradi’s previous website.", links: [sourceLink("Open the original page", "https://dralimoradi.com/bionic-hand-h5/")] },
    ], ctaTitle: "Control and fitting require training", ctaText: "A prosthetic-care team should configure grip modes and control thresholds for the individual user." },
    "innovation/integrated-stem": { kicker: "Innovation project", title: "Integrated segmented radius stem.", intro: "An intramedullary concept for trans-radial osseointegration challenges.", sections: [
      { title: "Clinical-engineering challenge", text: "Forearm osseointegration is constrained by the small diameter and variable curvature of the radius and ulna, together with two-bone mechanics and rotational movement." },
      { title: "Design response", text: "The archived project describes a segmented intramedullary radius stem intended to adapt to this anatomy while supporting an integrated prosthetic interface." },
      { title: "Development status", text: "The page presents a design direction rather than general clinical guidance; safety, fixation, loading, and long-term outcomes require formal evaluation." },
      { title: "Archived source", text: "This page restructures the approved content from Dr. Moradi’s previous website.", links: [sourceLink("Open the original page", "https://dralimoradi.com/integrated-stem/")] },
    ], ctaTitle: "A specialized development pathway", ctaText: "Osseointegration requires multidisciplinary assessment and carefully governed research." },
    "innovation/hip-exoskeleton-hexa": { kicker: "Innovation project", title: "Hip Exoskeleton HEXA.", intro: "A wearable robotic system developed to assist gait rehabilitation.", sections: [
      { title: "Purpose", text: "The archived project describes HEXA for people with lower-limb disability, including gait impairment after hemiplegic stroke and scissoring gait." },
      { title: "Actuation", text: "Hip-joint actuators use geared 70-watt brushless motors connected to thigh links, with the aim of supplying hip-flexion torque during the gait cycle." },
      { title: "Rehabilitation goals", text: "The listed goals include addressing gait abnormalities and supporting symmetry, stride, linear speed, and walking duration under supervised rehabilitation." },
      { title: "Archived source", text: "This page restructures the approved content from Dr. Moradi’s previous website.", links: [sourceLink("Open the original page", "https://dralimoradi.com/hip-exoskeleton-hexa/")] },
    ], ctaTitle: "Robotic rehabilitation needs supervision", ctaText: "Suitability and training parameters should be set by an appropriately qualified rehabilitation team." },
  },
  fa: {},
  ar: {},
};

const detailTranslations = {
  fa: {
    "innovation/dynamometer": ["دینامومتر کنترل دیسترکشن.", "پایش نیروی کشش در فیکساتور خارجی دینامیک دیستال رادیوس.", ["مسئله بالینی", "در درمان بسته شکستگی دیستال رادیوس، کشش بیش از حد می‌تواند فشار تاندون‌ها—به‌ویژه تاندون‌های اکستانسور—را افزایش دهد و به خشکی یا کاهش حرکت انگشتان منجر شود."], ["مفهوم", "دینامومتر همراه فیکساتور دینامیک استفاده می‌شود تا تیم درمان نیروی دیسترکشن را هنگام جراحی و ویزیت‌های پیگیری بررسی کند."], ["دو روش خواندن نیرو", "در طرح آرشیوی یک مقیاس کمی برای اندازه‌گیری مستقیم و یک مقیاس رنگی سبز، زرد و قرمز برای نمایش افزایش نیرو معرفی شده است."], "https://dralimoradi.com/external-fixators-1/"],
    "innovation/magnetic-joint-distraction": ["دیسترکشن مغناطیسی مفصل.", "مفهوم ایمپلنتی پژوهشی برای ایجاد دیسترکشن پایدار در مفصل CMC شست.", ["منطق طرح", "دیسترکشن مفصل به‌عنوان روشی برای کاهش بار مفصل آرتروزی و احتمال کاهش درد بررسی می‌شود و پروژه آرشیوی بر مفصل CMC1 تمرکز دارد."], ["مفهوم ایمپلنت", "طرح شامل دو ایمپلنت حاوی آهنربا در کپسول خنثی است که در تراپزیوم و متاکارپ اول قرار می‌گیرند."], ["سازوکار پیشنهادی", "قرارگیری قطب‌های هم‌نام روبه‌روی هم، نیروی دافعه پیوسته‌ای ایجاد می‌کند. این مسیر همچنان پژوهشی است و دستور درمان عمومی محسوب نمی‌شود."], "https://dralimoradi.com/magnetic-distractor/"],
    "innovation/dynamic-distal-radius-external-fixator": ["فیکساتور خارجی دینامیک دیستال رادیوس.", "تثبیت خارجی قابل‌تنظیم با دیسترکشن تدریجی و محور کشش مفصلی.", ["تثبیت خارجی", "پین‌های داخل استخوان به فریم بیرونی متصل می‌شوند تا استخوان را تثبیت و دسترسی به بافت نرم آسیب‌دیده را حفظ کنند؛ کاربرد آن به شرایط منتخب بستگی دارد."], ["ویژگی‌های طراحی", "دستگاه آرشیوی کم‌حجم و سبک توصیف شده و دارای کلمپ‌های قابل‌تنظیم، امکان جایگذاری منعطف پین و مهره دیسترکشن تدریجی است."], ["مفهوم بیومکانیکی", "مفصل‌های قابل‌تنظیم، جهت نیروی کشش را نسبت به سطح مفصلی دیستال رادیوس تنظیم می‌کنند تا لیگامنتوتاکسیس تحت کنترل جراح انجام شود."], "https://dralimoradi.com/external-fixators/"],
    "innovation/bionic-hand-h3": ["دست بیونیک H3.", "دست پروتزی با کنترل مایوالکتریک برای گرفتن سریع و کاربرد روزمره.", ["کاربران هدف", "شرح محصول H3 آن را برای افراد دارای فقدان اندام فوقانی در سطوح ترانس‌رادیال و بالاتر و استفاده یک‌طرفه یا دوطرفه معرفی می‌کند."], ["کنترل و عملکرد", "کنترل EMG، سرعت تناسبی، تشخیص لغزش، بازخورد نیروی گرفتن، سازوکار غیرقابل‌برگشت و رهاسازی ایمنی از ویژگی‌های درج‌شده هستند."], ["اندازه و محافظت", "سه اندازه، سازگاری با انواع مچ، پوسته PVC و دستکش ظاهری برای حفاظت از مکانیزم و ظاهر طبیعی‌تر معرفی شده‌اند."], "https://dralimoradi.com/bionic-hand-h3/"],
    "innovation/bionic-hand-h5": ["دست بیونیک H5.", "دست پروتزی چندحالته با انگشت‌های دارای محرک مستقل و کنترل قابل‌تنظیم.", ["طراحی", "شرح H5 شامل پنج انگشت با محرک مستقل، شست قابل‌تنظیم دستی، پنج موتور و حرکت مچ با مجموع هفت درجه آزادی است."], ["کنترل", "سامانه از کنترل EMG یا KMG، حرکت مستقل انگشتان، پاسخ تناسبی، حالت‌های مختلف گرفتن و بازخورد نیرو پشتیبانی می‌کند."], ["محافظت مکانیکی", "حرکت غیرقابل‌برگشت، جذب نیروی برخورد، تشخیص لغزش و سازوکار رهاسازی ایمنی برای کارهای روزانه در طرح ذکر شده‌اند."], "https://dralimoradi.com/bionic-hand-h5/"],
    "innovation/integrated-stem": ["استم قطعه‌ای یکپارچه رادیوس.", "مفهوم داخل‌استخوانی برای چالش‌های اوسئواینتگریشن ترانس‌رادیال.", ["چالش بالینی و مهندسی", "قطر کم و انحنای متغیر رادیوس و اولنا، مکانیک دو استخوان و حرکت چرخشی، اوسئواینتگریشن ساعد را دشوار می‌کنند."], ["پاسخ طراحی", "پروژه آرشیوی یک استم داخل‌استخوانی قطعه‌ای رادیوس را برای سازگاری با آناتومی و اتصال یکپارچه پروتز پیشنهاد می‌کند."], ["وضعیت توسعه", "این صفحه یک مسیر طراحی را معرفی می‌کند؛ ایمنی، تثبیت، بارگذاری و پیامد بلندمدت نیازمند ارزیابی رسمی هستند."], "https://dralimoradi.com/integrated-stem/"],
    "innovation/hip-exoskeleton-hexa": ["اسکلت بیرونی هیپ HEXA.", "سامانه رباتیک پوشیدنی برای کمک به توان‌بخشی راه‌رفتن.", ["هدف", "پروژه آرشیوی HEXA را برای افراد دارای اختلال اندام تحتانی از جمله اختلال راه‌رفتن پس از سکته همی‌پلژیک و گیت قیچی معرفی می‌کند."], ["محرک‌ها", "محرک‌های مفصل هیپ از موتورهای براشلس ۷۰ وات با گیربکس و لینک متصل به ران برای تأمین گشتاور فلکشن هیپ استفاده می‌کنند."], ["اهداف توان‌بخشی", "اصلاح الگوی راه‌رفتن و پشتیبانی از تقارن، طول گام، سرعت خطی و مدت راه‌رفتن تحت نظارت درمانگر از اهداف ذکرشده است."], "https://dralimoradi.com/hip-exoskeleton-hexa/"],
  },
  ar: {
    "innovation/dynamometer": ["مقياس قوة الإبعاد.", "مراقبة قوة الشد مع المثبت الخارجي الديناميكي للكعبرة البعيدة.", ["المشكلة السريرية", "قد يزيد الشد المفرط أثناء العلاج المغلق لكسور الكعبرة البعيدة توتر الأوتار، خصوصاً أوتار البسط، ويسهم في التيبس أو نقص حركة الأصابع."], ["المفهوم", "يُستخدم المقياس مع المثبت الديناميكي ليتحقق الفريق من قوة الإبعاد أثناء الجراحة وفي زيارات المتابعة."], ["نظاما القراءة", "يصف التصميم مقياساً كمياً للقوة ومقياساً لونياً بمناطق خضراء وصفراء وحمراء تدل على ارتفاع القوة."], "https://dralimoradi.com/external-fixators-1/"],
    "innovation/magnetic-joint-distraction": ["الإبعاد المغناطيسي للمفصل.", "مفهوم بحثي مزروع لإبعاد مستمر في مفصل قاعدة الإبهام.", ["المنطق", "يُبحث إبعاد المفصل لتخفيف الحمل عن المفصل المصاب بالفصال وربما تخفيف الألم، مع تركيز المشروع على CMC1."], ["مفهوم الزرع", "يقترح التصميم زوجاً من الغرسات تحتوي كل منهما على مغناطيس داخل كبسولة محايدة في العظم شبه المنحرف والمشط الأول."], ["الآلية المقترحة", "تتواجه الأقطاب المتشابهة لتوليد قوة تنافر مستمرة. يبقى هذا مساراً بحثياً وليس إرشاداً علاجياً عاماً."], "https://dralimoradi.com/magnetic-distractor/"],
    "innovation/dynamic-distal-radius-external-fixator": ["المثبت الخارجي الديناميكي للكعبرة البعيدة.", "تثبيت قابل للضبط بإبعاد تدريجي ومحور شد مفصلي.", ["التثبيت الخارجي", "تتصل المسامير داخل العظم بإطار خارجي لتثبيت العظم مع الحفاظ على الوصول إلى النسج الرخوة في حالات مختارة."], ["خصائص التصميم", "يوصف الجهاز بأنه خفيف ومنخفض البروز، بمشابك قابلة للضبط ووضع مرن للمسامير وصامولة للإبعاد التدريجي."], ["المفهوم الميكانيكي", "تسمح المفاصل القابلة للضبط بتوجيه قوة الشد بالنسبة إلى السطح المفصلي تحت تحكم الجراح."], "https://dralimoradi.com/external-fixators/"],
    "innovation/bionic-hand-h3": ["اليد الإلكترونية H3.", "يد تعويضية بتحكم عضلي كهربائي لقبض سريع في الحياة اليومية.", ["المستخدمون", "يعرض وصف H3 الجهاز لمستويات البتر عبر الكعبرة وما فوقها، وللاستخدام أحادي أو ثنائي الطرف."], ["التحكم والأداء", "تشمل الخصائص تحكم EMG والسرعة التناسبية وكشف الانزلاق واستشعار قوة القبضة وآلية عدم الرجوع والتحرير الآمن."], ["المقاسات والحماية", "ترد ثلاثة مقاسات وخيارات للمعصم وغلاف PVC وقفاز تجميلي لحماية الآلية وتحسين المظهر."], "https://dralimoradi.com/bionic-hand-h3/"],
    "innovation/bionic-hand-h5": ["اليد الإلكترونية H5.", "يد متعددة القبضات بأصابع ذات محركات مستقلة وتحكم قابل للتهيئة.", ["التصميم", "يصف H5 خمسة أصابع مستقلة وإبهاماً يضبط يدوياً وخمسة محركات وحركة للمعصم بإجمالي سبع درجات حرية."], ["التحكم", "يدعم النظام تحكم EMG أو KMG وحركة مستقلة للأصابع واستجابة تناسبية وأنماط قبض متعددة وتغذية راجعة للقوة."], ["الحماية الميكانيكية", "يشمل المفهوم حركة غير قابلة للرجوع وامتصاص الصدمات وكشف الانزلاق وآلية تحرير آمن."], "https://dralimoradi.com/bionic-hand-h5/"],
    "innovation/integrated-stem": ["الساق الكعبرية المدمجة المجزأة.", "مفهوم داخل النقي لتحديات الاندماج العظمي عبر الكعبرة.", ["التحدي", "يعقد ضيق الكعبرة والزند وانحناؤهما وميكانيكا عظمي الساعد وحركته الدورانية تصميم الاندماج العظمي."], ["استجابة التصميم", "يصف المشروع ساقاً كعبرية نخاعية مجزأة للتكيف مع التشريح ودعم واجهة تعويضية مدمجة."], ["حالة التطوير", "هذا اتجاه تصميمي؛ يجب تقييم السلامة والتثبيت والتحميل والنتائج طويلة المدى رسمياً."], "https://dralimoradi.com/integrated-stem/"],
    "innovation/hip-exoskeleton-hexa": ["الهيكل الخارجي للورك HEXA.", "نظام روبوتي قابل للارتداء لدعم تأهيل المشي.", ["الغرض", "يعرض المشروع HEXA للأشخاص ذوي إعاقات الطرف السفلي، ومنها اضطراب المشي بعد السكتة مع الشلل النصفي والمشية المقصية."], ["المحركات", "تستخدم محركات الورك عديمة الفُرش بقدرة ٧٠ واط مع علبة تروس وروابط للفخذ لتوفير عزم ثني الورك."], ["أهداف التأهيل", "تشمل الأهداف دعم التناسق وطول الخطوة والسرعة ومدة المشي تحت إشراف فريق التأهيل."], "https://dralimoradi.com/hip-exoskeleton-hexa/"],
  },
} as const;

for (const locale of ["fa", "ar"] as const) {
  const label = locale === "fa" ? "منبع آرشیوی" : "المصدر المؤرشف";
  const open = locale === "fa" ? "بازکردن صفحه اصلی" : "فتح الصفحة الأصلية";
  const kicker = locale === "fa" ? "پروژه نوآوری" : "مشروع ابتكار";
  const ctaTitle = locale === "fa" ? "اطلاعات پروژه نیازمند ارزیابی تخصصی است" : "تحتاج معلومات المشروع إلى تقييم تخصصي";
  const ctaText = locale === "fa" ? "این معرفی جایگزین ارزیابی بالینی، تجویز وسیله یا آموزش کاربری نیست." : "لا يستبدل هذا التعريف التقييم السريري أو وصف الجهاز أو تدريب الاستخدام.";
  for (const [route, value] of Object.entries(detailTranslations[locale])) {
    const [title, intro, first, second, third, url] = value;
    detailPages[locale][route] = {
      kicker,
      title,
      intro,
      sections: [
        { title: first[0], text: first[1] },
        { title: second[0], text: second[1] },
        { title: third[0], text: third[1] },
        { title: label, text: locale === "fa" ? "محتوای تأییدشده وب‌سایت قبلی در ساختار جدید بازآرایی شده است." : "أُعيد تنظيم المحتوى المعتمد من الموقع السابق ضمن البنية الجديدة.", links: [sourceLink(open, url)] },
      ],
      ctaTitle,
      ctaText,
    };
  }
}

const carePages: Record<Locale, Record<string, InteriorPageData>> = {
  fa: {
    "clinical-care/clinic-services": { kicker: "خدمات کلینیک", title: "مراقبت جامع دست، مچ و اندام فوقانی.", intro: "تشخیص تخصصی و درمان شخصی‌سازی‌شده برای شرایط انتخابی، دژنراتیو، ورزشی و ناشی از استفاده بیش از حد.", sections: [
      { title: "رویکرد کلینیک", text: "کلینیک، مدیریت پزشکی مبتنی بر شواهد را با روش‌های کم‌تهاجمی و جراحی ترکیب می‌کند تا درد کاهش یابد، عملکرد بازیابی شود و بازگشت ایمن به فعالیت‌های روزانه امکان‌پذیر باشد." },
      { title: "سندرم تونل کوبیتال", text: "فشردگی عصب اولنار در آرنج می‌تواند بی‌حسی انگشت حلقه و کوچک یا ضعف دست ایجاد کند. درمان با توجه به شدت علائم از مراقبت محافظه‌کارانه تا جراحی رفع فشار انتخاب می‌شود." },
      { title: "تنوسینوویت دکرون", text: "درد سمت شست مچ، به‌ویژه هنگام گرفتن یا بلندکردن اجسام، می‌تواند با آتل، تزریق و در موارد لازم آزادسازی جراحی تاندون درمان شود." },
      { title: "آسیب‌های ورزشی و رباطی", text: "آسیب رباط پس از افتادن، چرخش یا ضربه رخ می‌دهد. انگشت شست اسکی‌باز و پارگی TFCC می‌توانند درد، ضعف گرفتن، صدا یا ناپایداری ایجاد کنند و به درمان اختصاصی نیاز دارند." },
      { title: "آسیب‌های ناشی از استفاده بیش از حد", text: "حرکات تکراری در ورزش یا کار ممکن است درد، خشکی یا کاهش قدرت گرفتن ایجاد کنند. برنامه درمانی بر کنترل علائم، پیشگیری از عود و بازگشت ایمن به فعالیت تمرکز دارد." },
    ], ctaTitle: "درمان بر اساس تشخیص و نیاز فردی انتخاب می‌شود", ctaText: "برای ارزیابی تخصصی، تصاویر و مدارک درمانی مرتبط را همراه داشته باشید." },
    "clinical-care/hospital-services": { kicker: "خدمات بیمارستان", title: "مراقبت فوری و جراحی تخصصی در محیط مجهز.", intro: "درمان آسیب‌های پیچیده دست و اندام فوقانی، با امکان تثبیت شکستگی و ترمیم دقیق بافت نرم.", sections: [
      { title: "مراقبت بیمارستانی", text: "آسیب‌های حاد، شکستگی‌ها، بریدگی عمیق، آسیب تاندون یا عصب، دررفتگی، له‌شدگی و درد شدید پس از حادثه ممکن است به ارزیابی و درمان سریع بیمارستانی نیاز داشته باشند." },
      { title: "پارگی تاندون", text: "بریدگی تاندون‌های حرکت‌دهنده انگشتان یا مچ غالباً برای بازیابی حرکت به ترمیم جراحی به‌موقع و برنامه توان‌بخشی پس از آن نیاز دارد." },
      { title: "آسیب‌های عصبی و عروقی", text: "صدمه عصب یا رگ می‌تواند حس، حرکت یا گردش خون را مختل کند و در موارد منتخب به ترمیم دقیق میکروسکوپی نیاز داشته باشد." },
      { title: "شکستگی‌ها", text: "شکستگی دست، مچ یا ساعد برای جوش‌خوردن صحیح و بازیابی عملکرد به هم‌راستایی و تثبیت مناسب، متناسب با الگوی شکستگی، نیاز دارد." },
    ], ctaTitle: "آسیب تهدیدکننده جان یا اندام یک اورژانس است", ctaText: "در شرایط فوری منتظر نوبت معمول نمانید و فوراً به خدمات اورژانس مراجعه کنید." },
  },
  en: {},
  ar: {},
};

carePages.en["clinical-care/clinic-services"] = { kicker: "Clinic services", title: "Comprehensive hand, wrist, and upper-extremity care.", intro: "Specialist diagnosis and individualized treatment for elective, degenerative, sporting, and overuse conditions.", sections: [
  { title: "Clinic approach", text: "The clinic combines evidence-based medical management with advanced minimally invasive and surgical techniques to reduce pain, restore function, and support a confident return to daily activity." },
  { title: "Cubital tunnel syndrome", text: "Compression of the ulnar nerve at the elbow may cause numbness in the ring and little fingers or hand weakness. Treatment ranges from conservative care to decompression surgery when required." },
  { title: "De Quervain tenosynovitis", text: "Pain on the thumb side of the wrist, especially with gripping or lifting, may be treated with splinting, injection, and—when needed—surgical release of the affected tendons." },
  { title: "Sporting and ligament injuries", text: "Ligament injury may follow a fall, twist, or impact. Skier’s thumb and TFCC tears can cause pain, weak grip, clicking, or instability and require an individualized pathway." },
  { title: "Overuse conditions", text: "Repetitive work or sport may overload muscles and tendons, causing pain, stiffness, or reduced grip. Care focuses on symptom control, recurrence prevention, and safe return to activity." },
], ctaTitle: "Treatment follows the diagnosis and individual need", ctaText: "Bring relevant imaging and previous treatment records to a specialist assessment." };
carePages.en["clinical-care/hospital-services"] = { kicker: "Hospital services", title: "Urgent and specialist surgery in an equipped setting.", intro: "Care for complex hand and upper-extremity injuries, with fracture stabilization and precise soft-tissue repair when required.", sections: [
  { title: "Hospital care", text: "Acute fractures, deep cuts, tendon or nerve injuries, dislocations, crush injuries, and severe post-accident pain may require prompt hospital assessment and treatment." },
  { title: "Tendon lacerations", text: "Cuts to tendons that move the fingers or wrist often require timely surgical repair followed by a structured rehabilitation plan to recover motion." },
  { title: "Nerve and vascular injuries", text: "Damage to nerves or blood vessels may affect sensation, movement, or circulation and can require precise microsurgical treatment in selected cases." },
  { title: "Fractures", text: "Broken bones of the hand, wrist, or forearm require appropriate alignment and stabilization according to the fracture pattern to support healing and function." },
], ctaTitle: "A life- or limb-threatening injury is an emergency", ctaText: "Do not wait for routine booking when urgent emergency assessment is needed." };
carePages.ar["clinical-care/clinic-services"] = { kicker: "خدمات العيادة", title: "رعاية شاملة لليد والمعصم والطرف العلوي.", intro: "تشخيص تخصصي وعلاج فردي للحالات الاختيارية والتنكسية والرياضية وحالات الإفراط في الاستخدام.", sections: [
  { title: "نهج العيادة", text: "تجمع العيادة بين العلاج الطبي القائم على الدليل والتقنيات محدودة التدخل والجراحة لتخفيف الألم واستعادة الوظيفة والعودة الآمنة إلى النشاط." },
  { title: "متلازمة النفق المرفقي", text: "قد يسبب انضغاط العصب الزندي عند المرفق خدراً في البنصر والخنصر أو ضعف اليد. يمتد العلاج من الرعاية التحفظية إلى إزالة الضغط جراحياً عند الحاجة." },
  { title: "التهاب غمد وتر دي كيرفان", text: "يمكن علاج ألم جهة الإبهام في المعصم، خاصة مع الإمساك أو الرفع، بالجبيرة أو الحقن أو تحرير الأوتار جراحياً عند اللزوم." },
  { title: "إصابات الرياضة والأربطة", text: "قد تحدث إصابة الأربطة بعد السقوط أو الالتواء أو الصدمة. وقد تسبب إصابة إبهام المتزلج وتمزق TFCC ألماً أو ضعفاً أو طقطقة أو عدم ثبات." },
  { title: "إصابات الإفراط في الاستخدام", text: "قد تؤدي الحركات المتكررة في العمل أو الرياضة إلى الألم والتيبس ونقص قوة القبضة. يركز العلاج على الأعراض ومنع التكرار والعودة الآمنة." },
], ctaTitle: "يُختار العلاج وفق التشخيص والحاجة الفردية", ctaText: "أحضر الصور والتقارير العلاجية ذات الصلة إلى التقييم التخصصي." };
carePages.ar["clinical-care/hospital-services"] = { kicker: "خدمات المستشفى", title: "الرعاية العاجلة والجراحة التخصصية في بيئة مجهزة.", intro: "علاج إصابات اليد والطرف العلوي المعقدة مع تثبيت الكسور وإصلاح النسج الرخوة عند الحاجة.", sections: [
  { title: "رعاية المستشفى", text: "قد تحتاج الكسور الحادة والجروح العميقة وإصابات الأوتار أو الأعصاب والخلوع والسحق والألم الشديد بعد الحوادث إلى تقييم وعلاج سريع في المستشفى." },
  { title: "تمزقات الأوتار", text: "غالباً ما تحتاج الجروح التي تقطع أوتار الأصابع أو المعصم إلى إصلاح جراحي في الوقت المناسب وبرنامج تأهيل منظم لاستعادة الحركة." },
  { title: "إصابات الأعصاب والأوعية", text: "قد يؤثر تلف الأعصاب أو الأوعية في الحس والحركة والدوران ويحتاج في حالات مختارة إلى إصلاح مجهري دقيق." },
  { title: "الكسور", text: "تحتاج كسور اليد أو المعصم أو الساعد إلى محاذاة وتثبيت مناسبين بحسب نمط الكسر لدعم الالتئام والوظيفة." },
], ctaTitle: "الإصابة المهددة للحياة أو الطرف حالة طارئة", ctaText: "لا تنتظر الحجز الروتيني عندما تكون هناك حاجة إلى تقييم إسعافي عاجل." };

const beforeSurgery: Record<Locale, InteriorPageData> = {
  fa: { kicker: "منابع بیمار", title: "راهنمای آمادگی پیش از جراحی.", intro: "نسخه مرتب‌شده راهنمای بستری سرویس جراحی دست و اندام فوقانی دکتر علی مرادی؛ دستور اختصاصی جراح و بیمارستان همیشه اولویت دارد.", sections: [
    { title: "وسیله عمل و مشاوره‌ها", text: "اگر برای عمل وسیله یا ایمپلنت مشخصی لازم است، باید پیش از عمل تهیه و هماهنگی استریل آن هنگام بستری با بخش بیمارستان انجام شود. هر مشاوره درخواست‌شده، از جمله بیهوشی یا قلب، باید پیش از بستری تکمیل شود." },
    { title: "ناشتایی و روز عمل", text: "طبق فایل راهنما، هشت ساعت ناشتایی لازم است و از ساعت ۶ صبح روز عمل خوردن و نوشیدن ممنوع است. زمان دقیق را حتماً با تیم درمان تأیید کنید؛ هیچ دستور متفاوتی را بدون تأیید جراح یا بیمارستان اجرا نکنید." },
    { title: "بهداشت و آماده‌سازی", text: "پیش از بستری دوش بگیرید، ناخن‌ها را کوتاه کنید و لاک، زیورآلات و وسایل فلزی را از دست خارج کنید. برای حمام می‌توان از شوینده کلرهگزیدین ۲٪ یا شامپو بچه استفاده کرد و موهای ناحیه عمل فقط با ماشین اصلاح برداشته شود، نه تیغ." },
    { title: "مدارک موردنیاز", text: "برگه بستری، نتیجه مشاوره‌های درخواست‌شده، وسیله عمل و تأیید هماهنگی استریل، تصاویر رادیوگرافی، CT، MRI، نوار عصب و عضله و خلاصه یا گزارش عمل‌های قبلی را همراه داشته باشید." },
    { title: "لغو عمل", text: "اگر نیاز به لغو عمل دارید، حداقل دو روز زودتر از طریق پیامک به شماره ۰۹۰۵۴۵۰۱۱۴۱ اطلاع دهید." },
    { title: "نکات پس از عمل که باید از قبل بدانید", text: "پانسمان نباید باز یا خیس شود. معمولاً پیگیری یک هفته بعد انجام می‌شود مگر تاریخ دیگری تعیین شود. اندام را طبق دستور در آویز نگه دارید، فقط حرکات مجاز را انجام دهید و آنتی‌بیوتیک را خودسرانه مصرف نکنید. درد خارج از تحمل یا هر مشکل جدی نیازمند تماس با تیم درمان یا مراجعه فوری است." },
    { title: "فرم رضایت و تأیید آگاهی", text: "فرم یک‌صفحه‌ای را دانلود و چاپ کنید. بخش‌های مشخصات بیمار و عمل را تکمیل کنید و امضا را پس از مطالعه و طرح پرسش‌های خود انجام دهید. این فایل جایگزین فرم رسمی بیمارستان نیست.", links: [{ label: "دانلود فرم رضایت‌نامه PDF", href: "/downloads/pre-surgery-consent-form.pdf", download: true }] },
  ], ctaTitle: "دستور اختصاصی خود را پیش از روز عمل تأیید کنید", ctaText: "در صورت هر ابهام درباره دارو، ناشتایی، زمان مراجعه یا وسیله عمل با تیم درمان تماس بگیرید." },
  en: { kicker: "Patient resources", title: "Before-surgery preparation guide.", intro: "An organized version of the admission guidance for Dr. Ali Moradi’s hand and upper-extremity surgery service; individual surgeon and hospital instructions always take priority.", sections: [
    { title: "Implants, devices, and consultations", text: "If a specific implant or device is required, obtain it before surgery and coordinate sterilization with the hospital department on admission. Complete every requested consultation—including anesthesia or cardiology—before admission." },
    { title: "Fasting and the day of surgery", text: "The supplied guide states that eight hours of fasting are required and that no food or drink should be consumed from 6:00 AM on the day of surgery. Confirm the exact timing with the treating team and do not follow a conflicting instruction without approval." },
    { title: "Hygiene and preparation", text: "Shower before admission, trim nails, and remove nail polish, jewellery, and metal objects from the hand. The guide recommends 2% chlorhexidine wash or baby shampoo and using an electric clipper—not a razor—for hair removal near the operative area." },
    { title: "Documents to bring", text: "Bring the admission form, requested consultation reports, the required device and sterilization confirmation, radiographs, CT or MRI records, nerve-conduction studies, and summaries or reports from previous operations." },
    { title: "Cancellation", text: "If surgery must be cancelled, notify the service by SMS at least two days in advance using +98 905 450 1141." },
    { title: "Postoperative points to understand beforehand", text: "Do not open or wet the dressing. Follow-up is commonly one week later unless another date is given. Keep the limb in a sling and move only as permitted. Do not start antibiotics on your own. Intolerable pain or a serious problem needs prompt contact or urgent assessment." },
    { title: "Consent and acknowledgement form", text: "Download and print the one-page form. Complete the patient and procedure fields and sign after reading it and asking your questions. This file does not replace the hospital’s official consent process.", links: [{ label: "Download the consent form PDF", href: "/downloads/pre-surgery-consent-form.pdf", download: true }] },
  ], ctaTitle: "Confirm individual instructions before surgery day", ctaText: "Ask the treating team about any uncertainty involving medication, fasting, arrival time, or required equipment." },
  ar: { kicker: "موارد المريض", title: "دليل الاستعداد قبل الجراحة.", intro: "نسخة منظمة من إرشادات القبول لخدمة جراحة اليد والطرف العلوي للدكتور علي مرادي؛ تبقى تعليمات الجراح والمستشفى الفردية هي الأولوية.", sections: [
    { title: "الغرسات والأجهزة والاستشارات", text: "إذا كانت العملية تحتاج إلى غرسة أو جهاز محدد فيجب توفيره قبل الجراحة وتنسيق تعقيمه مع قسم المستشفى عند القبول. أكمل كل استشارة مطلوبة، ومنها التخدير أو القلب، قبل الدخول." },
    { title: "الصيام ويوم العملية", text: "ينص الدليل المرفق على صيام ثماني ساعات ومنع الطعام والشراب من الساعة السادسة صباحاً يوم العملية. أكد الوقت الدقيق مع الفريق ولا تتبع أي تعليمات متعارضة دون موافقته." },
    { title: "النظافة والاستعداد", text: "استحم قبل القبول وقص الأظافر وأزل الطلاء والحلي والأجسام المعدنية من اليد. يوصي الدليل بغسول كلورهكسيدين ٢٪ أو شامبو الأطفال وباستخدام ماكينة كهربائية لا شفرة لإزالة الشعر قرب موضع الجراحة." },
    { title: "الوثائق المطلوبة", text: "أحضر ورقة القبول وتقارير الاستشارات المطلوبة والجهاز وتأكيد التعقيم وصور الأشعة وCT وMRI وتخطيط الأعصاب والعضلات وملخصات أو تقارير العمليات السابقة." },
    { title: "إلغاء العملية", text: "إذا لزم إلغاء الجراحة فأبلغ الخدمة برسالة نصية قبل يومين على الأقل عبر الرقم ‎+98 905 450 1141‎." },
    { title: "نقاط ما بعد العملية التي ينبغي معرفتها مسبقاً", text: "لا تفتح الضماد ولا تبلله. تكون المتابعة غالباً بعد أسبوع ما لم يحدد موعد آخر. استخدم الحمالة وتحرك فقط حسب السماح، ولا تبدأ مضاداً حيوياً من تلقاء نفسك. الألم غير المحتمل أو المشكلة الخطيرة يحتاجان إلى تواصل أو تقييم عاجل." },
    { title: "نموذج الموافقة والإقرار", text: "نزّل النموذج ذي الصفحة الواحدة واطبعه. أكمل بيانات المريض والإجراء ووقع بعد القراءة وطرح الأسئلة. لا يحل هذا الملف محل نموذج المستشفى الرسمي.", links: [{ label: "تنزيل نموذج الموافقة PDF", href: "/downloads/pre-surgery-consent-form.pdf", download: true }] },
  ], ctaTitle: "أكد تعليماتك الفردية قبل يوم الجراحة", ctaText: "اسأل الفريق عن أي غموض يتعلق بالأدوية أو الصيام أو وقت الحضور أو الأجهزة المطلوبة." },
};

export const contentOverrides: Record<Locale, Record<string, InteriorPageData>> = {
  en: { innovation: innovationPage("en"), about: aboutPageCopy.en, ...detailPages.en, ...carePages.en, "patient-resources/before-surgery": beforeSurgery.en },
  fa: { innovation: innovationPage("fa"), about: aboutPageCopy.fa, ...detailPages.fa, ...carePages.fa, "patient-resources/before-surgery": beforeSurgery.fa },
  ar: { innovation: innovationPage("ar"), about: aboutPageCopy.ar, ...detailPages.ar, ...carePages.ar, "patient-resources/before-surgery": beforeSurgery.ar },
};

export const contentOverrideRoutes = Object.keys(contentOverrides.en).filter((route) => route !== "about" && route !== "innovation");
