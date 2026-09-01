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
  sourceUrl?: string;
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
    image: "/media/team/ali-moradi.jpg",
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
    image: "/media/team/mona-meybodi.png",
    areas: ["clinic"],
  },
  {
    slug: "mahsa-jafari",
    name: { en: "Dr. Mahsa Jafari", fa: "دکتر مهسا جعفری", ar: "الدكتورة مهسا جعفري" },
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
    image: "/media/team/mahsa-jafari.png",
    areas: ["clinic"],
  },
  {
    slug: "mahla-daliri",
    name: { en: "Dr. Mahla Daliri", fa: "دکتر مهلا دلیری", ar: "الدكتورة مهلا دليري" },
    role: { en: "Physician, clinical researcher, and medical innovator", fa: "پزشک، پژوهشگر بالینی و نوآور پزشکی", ar: "طبيبة وباحثة سريرية ومبتكرة طبية" },
    summary: {
      en: "Works across orthopedics, biomechanics, bionic limbs, rehabilitation robotics, and medical-device innovation.",
      fa: "فعال در پیوند ارتوپدی، بیومکانیک، اندام‌های بیونیک، رباتیک توان‌بخشی و نوآوری تجهیزات پزشکی.",
      ar: "تعمل عند تقاطع جراحة العظام والميكانيكا الحيوية والأطراف الحيوية وروبوتات التأهيل وابتكار الأجهزة الطبية.",
    },
    bio: {
      en: "Dr. Mahla Daliri earned her MD from Mashhad University of Medical Sciences in 2022 and continued as a clinical research fellow at its Orthopedics Research Center. Her work spans musculoskeletal research, biomechanics, upper-extremity rehabilitation, external fixation, prosthetics, bionic hands, and rehabilitation robotics. She has contributed to peer-reviewed publications, a book, and national and international patents, with a sustained focus on translating interdisciplinary medical-engineering research into practical technologies.",
      fa: "دکتر مهلا دلیری در سال ۲۰۲۲ مدرک پزشکی خود را از دانشگاه علوم پزشکی مشهد دریافت کرد و فعالیتش را به‌عنوان پژوهشگر بالینی در مرکز تحقیقات ارتوپدی ادامه داد. حوزه کار او پژوهش‌های اسکلتی‌عضلانی، بیومکانیک، توان‌بخشی اندام فوقانی، فیکساتورهای خارجی، پروتز، دست‌های بیونیک و رباتیک توان‌بخشی را دربرمی‌گیرد. او در مقاله‌های علمی، یک کتاب و پتنت‌های ملی و بین‌المللی مشارکت داشته و بر تبدیل پژوهش میان‌رشته‌ای پزشکی و مهندسی به فناوری کاربردی تمرکز دارد.",
      ar: "حصلت الدكتورة مهلا دليري على شهادة الطب من جامعة مشهد للعلوم الطبية عام 2022، ثم واصلت عملها كباحثة سريرية في مركز أبحاث جراحة العظام. تشمل اهتماماتها الاضطرابات العضلية الهيكلية والميكانيكا الحيوية وتأهيل الطرف العلوي والمثبتات الخارجية والأطراف التعويضية والأيدي الحيوية وروبوتات التأهيل. ساهمت في أبحاث محكمة وكتاب وبراءات اختراع وطنية ودولية، مع تركيز مستمر على تحويل البحث الطبي الهندسي متعدد التخصصات إلى تقنيات عملية.",
    },
    image: "/media/team/mahla-daliri.png",
    areas: ["research", "innovation"],
    sourceUrl: "https://orthopresearch.com/index.php/component/sppagebuilder/?view=page&id=40",
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
    image: "/media/team/afsaneh-jahani.png",
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
    image: "/media/team/maedeh-sharafoddin.png",
    areas: ["research", "innovation"],
  },
  {
    slug: "naeemeh-kalali",
    name: { en: "Naeemeh Kalali", fa: "نعیمه کلالی", ar: "نعيمة كلالي" },
    role: { en: "Laboratory specialist and researcher", fa: "کارشناس آزمایشگاه و پژوهشگر", ar: "اختصاصية مختبر وباحثة" },
    summary: {
      en: "Contributes laboratory expertise in biology and medical microbiology to bone and joint research.",
      fa: "همکاری تخصصی آزمایشگاهی در زیست‌شناسی و میکروبیولوژی پزشکی برای پژوهش استخوان و مفاصل.",
      ar: "تقدم خبرة مختبرية في الأحياء والأحياء الدقيقة الطبية لبحوث العظام والمفاصل.",
    },
    bio: {
      en: "Naeemeh Kalali supports laboratory research with a background in biology and medical microbiology. Her work contributes to reliable experimental workflows and interdisciplinary studies.",
      fa: "نعیمه کلالی با پیشینه زیست‌شناسی و میکروبیولوژی پزشکی در پژوهش‌های آزمایشگاهی مشارکت دارد و از فرایندهای تجربی قابل‌اعتماد و مطالعات میان‌رشته‌ای پشتیبانی می‌کند.",
      ar: "تشارك نعيمة كلالي في البحث المختبري بخلفية في الأحياء والأحياء الدقيقة الطبية وتدعم الدراسات متعددة التخصصات.",
    },
    image: "/media/team/naeemeh-kalali.png",
    areas: ["research", "innovation"],
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

const labMember = (
  slug: string,
  names: LocalizedText,
  roles: LocalizedText,
  field: LocalizedText,
  image: string,
  sourceUrl: string,
): TeamMember => ({
  slug,
  name: names,
  role: roles,
  summary: {
    en: `Contributes ${field.en.toLowerCase()} expertise to the Bone and Joint Research Laboratory’s interdisciplinary program.`,
    fa: `همکاری تخصصی در حوزه ${field.fa} با برنامه میان‌رشته‌ای آزمایشگاه پژوهشی استخوان و مفاصل.`,
    ar: `يسهم بخبرة في ${field.ar} ضمن برنامج مختبر أبحاث العظام والمفاصل متعدد التخصصات.`,
  },
  bio: {
    en: `${names.en} is a member of the Bone and Joint Research Laboratory at Mashhad University of Medical Sciences. Their work is centered on ${field.en.toLowerCase()} and supports collaborative orthopedic research, reliable study workflows, and the translation of scientific questions into clinically relevant evidence and technology.`,
    fa: `${names.fa} از اعضای آزمایشگاه پژوهشی استخوان و مفاصل دانشگاه علوم پزشکی مشهد است. فعالیت حرفه‌ای او بر ${field.fa} متمرکز است و از پژوهش مشترک ارتوپدی، اجرای منظم مطالعات و تبدیل پرسش‌های علمی به شواهد و فناوری‌های مرتبط با نیازهای بالینی پشتیبانی می‌کند.`,
    ar: `${names.ar} عضو في مختبر أبحاث العظام والمفاصل بجامعة مشهد للعلوم الطبية. يتركز العمل على ${field.ar} ويدعم البحث التعاوني في جراحة العظام وسير الدراسات الموثوق وتحويل الأسئلة العلمية إلى أدلة وتقنيات ذات صلة سريرية.`,
  },
  image,
  areas: ["research", "innovation"],
  sourceUrl,
});

teamMembers.push(
  {
    slug: "mohammadreza-yazdanpanah",
    name: { en: "Dr. Mohammadreza Yazdanpanah", fa: "دکتر محمدرضا یزدان‌پناه", ar: "الدكتور محمد رضا يزدان بناه" },
    role: { en: "Clinic physician", fa: "پزشک همکار کلینیک", ar: "طبيب متعاون في العيادة" },
    summary: { en: "Supports coordinated clinical assessment and follow-up within the specialist clinic.", fa: "همکاری در ارزیابی بالینی و پیگیری هماهنگ بیماران در کلینیک تخصصی.", ar: "يسهم في التقييم السريري والمتابعة المنسقة داخل العيادة التخصصية." },
    bio: { en: "Dr. Mohammadreza Yazdanpanah works with the specialist clinic team to support organized assessment, documentation, and follow-up. His profile is currently presented as an interim overview and will be expanded when the approved professional biography is supplied.", fa: "دکتر محمدرضا یزدان‌پناه در کنار تیم کلینیک تخصصی از ارزیابی منظم، مستندسازی و پیگیری بیماران پشتیبانی می‌کند. این معرفی فعلاً نسخه اولیه است و پس از دریافت رزومه تأییدشده تکمیل خواهد شد.", ar: "يعمل الدكتور محمد رضا يزدان بناه مع فريق العيادة التخصصية لدعم التقييم المنظم والتوثيق والمتابعة. هذا التعريف مؤقت وسيُستكمل بعد استلام السيرة المهنية المعتمدة." },
    image: "/media/team/mohammadreza-yazdanpanah.png", areas: ["clinic"],
  },
  {
    slug: "maryam-jafari",
    name: { en: "Maryam Jafari", fa: "مریم جعفری", ar: "مريم جعفري" },
    role: { en: "Clinic administration coordinator", fa: "هماهنگ‌کننده امور اجرایی کلینیک", ar: "منسقة الإدارة في العيادة" },
    summary: { en: "Supports communication, documentation, and the organized day-to-day patient journey.", fa: "پشتیبانی از ارتباطات، مستندسازی و مسیر منظم مراجعه بیماران.", ar: "تدعم التواصل والتوثيق وتنظيم رحلة المراجعين اليومية." },
    bio: { en: "Maryam Jafari supports the clinic’s administrative workflow and patient communication. Her role helps keep documentation, coordination, and day-to-day service delivery clear and organized. This interim profile will be updated after the approved professional background is received.", fa: "مریم جعفری از فرایندهای اجرایی و ارتباط با مراجعه‌کنندگان در کلینیک پشتیبانی می‌کند. نقش او به نظم مستندات، هماهنگی و ارائه روشن خدمات روزانه کمک می‌کند. این معرفی اولیه پس از دریافت سوابق تأییدشده تکمیل می‌شود.", ar: "تدعم مريم جعفري سير العمل الإداري والتواصل مع المراجعين، وتساعد في تنظيم التوثيق والتنسيق والخدمة اليومية. سيُحدّث هذا الملف المؤقت بعد استلام المعلومات المهنية المعتمدة." },
    image: "/media/team/maryam-jafari.png", areas: ["clinic"],
  },
  {
    slug: "mojtaba-asadpour",
    name: { en: "Mojtaba Asadpour", fa: "مجتبی اسدپور", ar: "مجتبى أسدبور" },
    role: { en: "Clinic operations team", fa: "همکار اجرایی کلینیک", ar: "فريق تشغيل العيادة" },
    summary: { en: "Supports coordinated clinic operations and the practical flow of patient services.", fa: "پشتیبانی از عملیات هماهنگ کلینیک و جریان اجرایی خدمات بیماران.", ar: "يدعم تشغيل العيادة وتدفق خدمات المرضى بصورة منسقة." },
    bio: { en: "Mojtaba Asadpour contributes to the practical coordination of clinic operations and patient services. This page is an interim profile and will be expanded with an approved portrait and verified professional background when supplied.", fa: "مجتبی اسدپور در هماهنگی اجرایی فعالیت‌های کلینیک و خدمات مراجعه‌کنندگان همکاری دارد. این صفحه معرفی اولیه است و پس از دریافت تصویر و سوابق حرفه‌ای تأییدشده تکمیل خواهد شد.", ar: "يسهم مجتبى أسدبور في التنسيق العملي لعمليات العيادة وخدمات المراجعين. هذا ملف مؤقت وسيُستكمل بصورة وسيرة مهنية معتمدتين عند توفرهما." },
    image: "/media/team/profile-placeholder.svg", areas: ["clinic"],
  },
  {
    slug: "mohammad-reza-akbarzadeh",
    name: { en: "Dr. Mohammad Reza Akbarzadeh", fa: "دکتر محمدرضا اکبرزاده", ar: "الدكتور محمد رضا أكبرزاده" },
    role: { en: "Innovation collaborator", fa: "همکار حوزه نوآوری", ar: "متعاون في الابتكار" },
    summary: { en: "Contributes to interdisciplinary medical-engineering innovation and product-oriented research.", fa: "همکاری در نوآوری میان‌رشته‌ای پزشکی و مهندسی و پژوهش محصول‌محور.", ar: "يسهم في الابتكار الطبي الهندسي والبحث الموجه نحو المنتجات." },
    bio: { en: "Dr. Mohammad Reza Akbarzadeh collaborates on selected interdisciplinary innovation activities connecting clinical needs, engineering analysis, and product development. This interim profile will be replaced with the approved biography and portrait when provided.", fa: "دکتر محمدرضا اکبرزاده در فعالیت‌های منتخب نوآوری میان‌رشته‌ای که نیاز بالینی، تحلیل مهندسی و توسعه محصول را به هم متصل می‌کنند همکاری دارد. این معرفی پس از دریافت رزومه و تصویر تأییدشده تکمیل می‌شود.", ar: "يتعاون الدكتور محمد رضا أكبرزاده في أنشطة ابتكارية مختارة تربط الاحتياجات السريرية بالتحليل الهندسي وتطوير المنتجات. سيُستكمل هذا الملف بالسيرة والصورة المعتمدتين عند توفرهما." },
    image: "/media/team/profile-placeholder.svg", areas: ["innovation"],
  },
  {
    slug: "nafiseh-jirofti",
    name: { en: "Dr. Nafiseh Jirofti", fa: "دکتر نفیسه جیرفتی", ar: "الدكتورة نفيسة جيرفتي" },
    role: { en: "Bone and Joint Research Laboratory manager", fa: "مدیر آزمایشگاه پژوهشی استخوان و مفاصل", ar: "مديرة مختبر أبحاث العظام والمفاصل" },
    summary: { en: "Leads interdisciplinary tissue-engineering, biomaterials, and orthopedic laboratory research.", fa: "هدایت پژوهش میان‌رشته‌ای مهندسی بافت، مواد زیستی و آزمایشگاه ارتوپدی.", ar: "تقود أبحاث هندسة الأنسجة والمواد الحيوية ومختبر جراحة العظام." },
    bio: { en: "Dr. Nafiseh Jirofti is an Assistant Professor of Tissue Engineering and manager of the Bone and Joint Research Laboratory. She earned her PhD in Chemical Engineering with a tissue-engineering focus in 2018 and completed four years of postdoctoral research at Mashhad University of Medical Sciences. Her work includes hybrid biopolymer scaffolds, drug-loaded bioinks, freeze-drying and bioprinting, cell culture, stem-cell extraction, antibacterial bioceramics, and orthopedic implant coatings. She has managed research laboratories, supervised more than thirty proposals, and contributed to more than forty journal and conference publications.", fa: "دکتر نفیسه جیرفتی استادیار مهندسی بافت و مدیر آزمایشگاه پژوهشی استخوان و مفاصل است. او دکترای مهندسی شیمی با گرایش مهندسی بافت را در سال ۲۰۱۸ دریافت کرد و چهار سال پژوهش پسادکتری را در دانشگاه علوم پزشکی مشهد گذراند. حوزه‌های فعالیتش شامل داربست‌های زیست‌پلیمری هیبریدی، بایواینک‌های دارورسان، فریزدرایینگ و بایوپرینتینگ، کشت سلول، استخراج سلول‌های بنیادی، بیوسرامیک‌های ضدباکتری و پوشش ایمپلنت‌های ارتوپدی است. او سابقه مدیریت آزمایشگاه، راهنمایی بیش از سی پروپوزال و مشارکت در بیش از چهل مقاله و ارائه علمی را دارد.", ar: "الدكتورة نفيسة جيرفتي أستاذة مساعدة في هندسة الأنسجة ومديرة مختبر أبحاث العظام والمفاصل. حصلت على الدكتوراه في الهندسة الكيميائية مع تركيز على هندسة الأنسجة عام 2018، وأكملت أربع سنوات من أبحاث ما بعد الدكتوراه في جامعة مشهد للعلوم الطبية. تشمل أعمالها السقالات البوليمرية الحيوية الهجينة والأحبار الحيوية المحملة بالأدوية والتجفيف بالتجميد والطباعة الحيوية وزراعة الخلايا واستخراج الخلايا الجذعية والسيراميك الحيوي المضاد للبكتيريا وطلاءات الغرسات العظمية. أدارت مختبرات بحثية وأشرفت على أكثر من ثلاثين مقترحاً وأسهمت في أكثر من أربعين بحثاً وعرضاً علمياً." },
    image: "/media/team/nafiseh-jirofti.png", areas: ["research", "innovation"], sourceUrl: "https://orthopresearch.com/index.php/component/sppagebuilder/?view=page&id=54",
  },
  labMember("fatemeh-koohzad", { en: "Dr. Fatemeh Koohzad", fa: "دکتر فاطمه کوهزاد", ar: "الدكتورة فاطمة كوهزاد" }, { en: "Postdoctoral researcher", fa: "پژوهشگر پسادکتری", ar: "باحثة ما بعد الدكتوراه" }, { en: "biochemistry", fa: "بیوشیمی", ar: "الكيمياء الحيوية" }, "/media/team/fatemeh-koohzad.png", "https://orthopresearch.com/index.php/component/sppagebuilder/?view=page&id=109"),
  labMember("azar-gharib", { en: "Dr. Azar Gharib", fa: "دکتر آذر غریب", ar: "الدكتورة آذر غريب" }, { en: "Postdoctoral researcher", fa: "پژوهشگر پسادکتری", ar: "باحثة ما بعد الدكتوراه" }, { en: "physical chemistry", fa: "شیمی فیزیک", ar: "الكيمياء الفيزيائية" }, "/media/team/azar-gharib.png", "https://orthopresearch.com/index.php/component/sppagebuilder/?view=page&id=104"),
  labMember("mehrnoush-nakhaei", { en: "Dr. Mehrnoush Nakhaei", fa: "دکتر مهرنوش نخعی", ar: "الدكتورة مهرنوش نخعي" }, { en: "Postdoctoral researcher", fa: "پژوهشگر پسادکتری", ar: "باحثة ما بعد الدكتوراه" }, { en: "solid-state physics", fa: "فیزیک حالت جامد", ar: "فيزياء الحالة الصلبة" }, "/media/team/mehrnoush-nakhaei.png", "https://orthopresearch.com/index.php/component/sppagebuilder/?view=page&id=55"),
  labMember("pouya-youseflee", { en: "Dr. Pouya Youseflee", fa: "دکتر پویا یوسفی‌لی", ar: "الدكتور بويا يوسفي لي" }, { en: "Laboratory researcher", fa: "پژوهشگر آزمایشگاه", ar: "باحث في المختبر" }, { en: "clinical medicine", fa: "پزشکی بالینی", ar: "الطب السريري" }, "/media/team/pouya-youseflee.jpg", "https://orthopresearch.com/index.php/component/sppagebuilder/?view=page&id=129"),
  labMember("mohamad-soveyzi", { en: "Mohamad Soveyzi", fa: "محمد سویزی", ar: "محمد سويزي" }, { en: "Laboratory researcher", fa: "پژوهشگر آزمایشگاه", ar: "باحث في المختبر" }, { en: "medical genetics", fa: "ژنتیک پزشکی", ar: "الوراثة الطبية" }, "/media/team/mohamad-soveyzi.jpg", "https://orthopresearch.com/index.php/component/sppagebuilder/?view=page&id=110"),
  labMember("mahdi-ahmadi", { en: "Mahdi Ahmadi", fa: "مهدی احمدی", ar: "مهدي أحمدي" }, { en: "PharmD student researcher", fa: "پژوهشگر دانشجوی داروسازی", ar: "باحث وطالب صيدلة" }, { en: "pharmacy", fa: "داروسازی", ar: "الصيدلة" }, "/media/team/mahdi-ahmadi.jpg", "https://orthopresearch.com/index.php/component/sppagebuilder/?view=page&id=108"),
  labMember("mohadese-khorasani", { en: "Dr. Mohadese Mohadesi Khorasani", fa: "دکتر محدثه محدثی خراسانی", ar: "الدكتورة محدثة محدثي خراساني" }, { en: "PharmD researcher", fa: "پژوهشگر داروسازی", ar: "باحثة في الصيدلة" }, { en: "regenerative medicine and drug delivery", fa: "پزشکی بازساختی و دارورسانی", ar: "الطب التجديدي وتوصيل الدواء" }, "/media/team/mohadese-khorasani.png", "https://orthopresearch.com/index.php/component/sppagebuilder/?view=page&id=107"),
  labMember("nik-qazvini", { en: "Nik Qazvini", fa: "نیک قزوینی", ar: "نيك قزويني" }, { en: "PharmD student researcher", fa: "پژوهشگر دانشجوی داروسازی", ar: "باحث وطالب صيدلة" }, { en: "pharmacy", fa: "داروسازی", ar: "الصيدلة" }, "/media/team/nik-qazvini.jpg", "https://orthopresearch.com/index.php/component/sppagebuilder/?view=page&id=117"),
  labMember("mohammad-amin-khojastehnezhad", { en: "Dr. Mohammad Amin Khojastehnezhad", fa: "دکتر محمدامین خجسته‌نژاد", ar: "الدكتور محمد أمين خجسته نجاد" }, { en: "Medical and biomedical-engineering researcher", fa: "پژوهشگر پزشکی و مهندسی پزشکی", ar: "باحث في الطب والهندسة الطبية" }, { en: "biomechanics and rehabilitation robotics", fa: "بیومکانیک و رباتیک توان‌بخشی", ar: "الميكانيكا الحيوية وروبوتات التأهيل" }, "/media/team/mohammad-amin-khojastehnezhad.jpg", "https://orthopresearch.com/index.php/component/sppagebuilder/?view=page&id=119"),
  labMember("mahsa-shariatrazavi", { en: "Dr. Sayyedeh Mahsa Shariatrazavi", fa: "دکتر سیده مهسا شریعت‌رضوی", ar: "الدكتورة سيدة مهسا شريعت رضوي" }, { en: "Medical researcher", fa: "پژوهشگر پزشکی", ar: "باحثة طبية" }, { en: "clinical orthopedic research", fa: "پژوهش بالینی ارتوپدی", ar: "البحث السريري في جراحة العظام" }, "/media/team/mahsa-shariatrazavi.png", "https://orthopresearch.com/index.php/component/sppagebuilder/?view=page&id=118"),
  labMember("saba-banaian", { en: "Saba Banaian", fa: "صبا بنائیان", ar: "صبا بنائيان" }, { en: "Mechanical-engineering researcher", fa: "پژوهشگر مهندسی مکانیک", ar: "باحثة في الهندسة الميكانيكية" }, { en: "mechanical engineering", fa: "مهندسی مکانیک", ar: "الهندسة الميكانيكية" }, "/media/team/saba-banaian.png", "https://orthopresearch.com/index.php/component/sppagebuilder/?view=page&id=120"),
  labMember("mehraneh-movahedi", { en: "Dr. Mehraneh Movahedi Aliabadi", fa: "دکتر مهرانه موحدی علی‌آبادی", ar: "الدكتورة مهرانه موحدي علي آبادي" }, { en: "Medical researcher", fa: "پژوهشگر پزشکی", ar: "باحثة طبية" }, { en: "clinical orthopedic research", fa: "پژوهش بالینی ارتوپدی", ar: "البحث السريري في جراحة العظام" }, "/media/team/mehraneh-movahedi.png", "https://orthopresearch.com/index.php/component/sppagebuilder/?view=page&id=127"),
  labMember("saeedeh-ebrahimzadeh", { en: "Saeedeh Ebrahimzadeh Bideskan", fa: "سعیده ابراهیم‌زاده بیدسکان", ar: "سعيدة إبراهيم زاده بيدسكان" }, { en: "Physiotherapy researcher", fa: "پژوهشگر فیزیوتراپی", ar: "باحثة في العلاج الطبيعي" }, { en: "physiotherapy", fa: "فیزیوتراپی", ar: "العلاج الطبيعي" }, "/media/team/saeedeh-ebrahimzadeh.png", "https://orthopresearch.com/index.php/component/sppagebuilder/?view=page&id=124"),
  labMember("seyed-mohamad-ebadi-rad", { en: "Dr. Seyed Mohamad Ebadi Rad", fa: "دکتر سید محمد عبادی‌راد", ar: "الدكتور سيد محمد عبادي راد" }, { en: "Medical researcher", fa: "پژوهشگر پزشکی", ar: "باحث طبي" }, { en: "clinical orthopedic research", fa: "پژوهش بالینی ارتوپدی", ar: "البحث السريري في جراحة العظام" }, "/media/team/seyed-mohamad-ebadi-rad.jpg", "https://orthopresearch.com/index.php/component/sppagebuilder/?view=page&id=126"),
  labMember("hamidreza-jamali", { en: "Hamidreza Jamali", fa: "حمیدرضا جمالی", ar: "حميد رضا جمالي" }, { en: "Student researcher", fa: "پژوهشگر دانشجو", ar: "باحث طالب" }, { en: "cell and molecular biology", fa: "زیست‌شناسی سلولی و مولکولی", ar: "الأحياء الخلوية والجزيئية" }, "/media/team/hamidreza-jamali.jpg", "https://orthopresearch.com/index.php/component/sppagebuilder/?view=page&id=128"),
  labMember("ghazaleh-hajiaghajani", { en: "Ghazaleh Hajiaghajani", fa: "غزاله حاجی‌آقاجانی", ar: "غزالة حاجي آقاجاني" }, { en: "Biomedical-engineering researcher", fa: "پژوهشگر مهندسی پزشکی", ar: "باحثة في الهندسة الطبية" }, { en: "bioelectric engineering", fa: "مهندسی بیوالکتریک", ar: "الهندسة الكهربائية الحيوية" }, "/media/team/ghazaleh-hajiaghajani.png", "https://orthopresearch.com/index.php/component/sppagebuilder/?view=page&id=76"),
);

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
  en: { kicker: "People", title: "Meet the team", intro: "Clinical, research, and engineering work is delivered through coordinated specialist roles.", readProfile: "Read More", back: "Back to the team", profileIntro: "Professional profile", expertise: "Area of work", collaboration: "Contribution to the team" },
  fa: { kicker: "اعضای تیم", title: "آشنایی با تیم", intro: "فعالیت بالینی، پژوهشی و مهندسی با همکاری نقش‌های تخصصی و هماهنگ پیش می‌رود.", readProfile: "بیشتر بخوانید", back: "بازگشت به اعضای تیم", profileIntro: "پروفایل حرفه‌ای", expertise: "حوزه فعالیت", collaboration: "نقش در تیم" },
  ar: { kicker: "الفريق", title: "تعرّف إلى الفريق", intro: "تتقدم الأعمال السريرية والبحثية والهندسية عبر أدوار تخصصية منسقة.", readProfile: "اقرأ المزيد", back: "العودة إلى الفريق", profileIntro: "الملف المهني", expertise: "مجال العمل", collaboration: "الدور في الفريق" },
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
      { slug: "clinical-care/clinic-services", title: "Clinic services", text: "Consultation, selected office procedures, follow-up, and early recovery guidance.", image: "/media/clinic/clinic-services-cover.jpg" },
      { slug: "clinical-care/hospital-services", title: "Hospital services", text: "Major and subspecialty operations planned for an appropriately equipped hospital.", image: "/media/clinic/hospital-services-cover.jpg" },
    ],
    clinicGalleryTitle: "Clinic surgical cases", clinicGalleryIntro: "Selected outpatient and focused hand-surgery cases managed through the clinic pathway.", hospitalGalleryTitle: "Hospital surgical cases", hospitalGalleryIntro: "Selected complex reconstructions and hospital-based upper-extremity operations.", viewGallery: "View full gallery", previous: "Previous image", next: "Next image", close: "Close gallery", backToClinic: "Back to the main clinic page",
  },
  fa: {
    pathwaysKicker: "محل ارائه خدمات", pathwaysTitle: "مسیر مناسب مراقبت را انتخاب کنید", pathwaysIntro: "خدمات سرپایی کلینیک و جراحی‌های بیمارستانی در دو مسیر مستقل معرفی شده‌اند.",
    pathways: [
      { slug: "clinical-care/clinic-services", title: "خدمات کلینیک", text: "مشاوره، اقدامات منتخب مطب، پیگیری و راهنمای اولیه دوران بهبود.", image: "/media/clinic/clinic-services-cover.jpg" },
      { slug: "clinical-care/hospital-services", title: "خدمات بیمارستان", text: "جراحی‌های بزرگ و فوق‌تخصصی در بیمارستان مجهز و متناسب با نیاز بیمار.", image: "/media/clinic/hospital-services-cover.jpg" },
    ],
    clinicGalleryTitle: "نمونه‌های جراحی کلینیک", clinicGalleryIntro: "منتخبی از اقدامات متمرکز و جراحی‌های دست در مسیر خدمات کلینیک.", hospitalGalleryTitle: "نمونه‌های جراحی بیمارستان", hospitalGalleryIntro: "منتخبی از بازسازی‌های پیچیده و جراحی‌های اندام فوقانی در بیمارستان.", viewGallery: "مشاهده گالری کامل", previous: "تصویر قبلی", next: "تصویر بعدی", close: "بستن گالری", backToClinic: "بازگشت به صفحه اصلی کلینیک",
  },
  ar: {
    pathwaysKicker: "مواقع الرعاية", pathwaysTitle: "اختر بيئة الرعاية المناسبة", pathwaysIntro: "تُعرض خدمات العيادة والجراحة في المستشفى ضمن مسارين واضحين.",
    pathways: [
      { slug: "clinical-care/clinic-services", title: "خدمات العيادة", text: "الاستشارة والإجراءات المختارة والمتابعة وإرشادات التعافي الأولية.", image: "/media/clinic/clinic-services-cover.jpg" },
      { slug: "clinical-care/hospital-services", title: "خدمات المستشفى", text: "العمليات الكبرى والتخصصية في مستشفى مجهز يناسب احتياجات المريض.", image: "/media/clinic/hospital-services-cover.jpg" },
    ],
    clinicGalleryTitle: "نماذج جراحات العيادة", clinicGalleryIntro: "حالات مختارة من الإجراءات المركزة وجراحات اليد ضمن مسار العيادة.", hospitalGalleryTitle: "نماذج جراحات المستشفى", hospitalGalleryIntro: "نماذج مختارة من الترميمات المعقدة وجراحات الطرف العلوي في المستشفى.", viewGallery: "عرض المعرض الكامل", previous: "الصورة السابقة", next: "الصورة التالية", close: "إغلاق المعرض", backToClinic: "العودة إلى صفحة العيادة الرئيسية",
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
