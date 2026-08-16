import type { Locale } from "./site-content";

type Service = { title: string; text: string };
type Faq = { question: string; answer: string };

export type ClinicCopy = {
  servicesTitle: string;
  servicesIntro: string;
  services: Service[];
  officeTitle: string;
  officeText: string[];
  officeImages: string[];
  serviceTypeTitle: string;
  serviceTypeText: string;
  scopeTitle: string;
  scopeItems: string[];
  faqTitle: string;
  faqIntro: string;
  faqs: Faq[];
};

export const clinicCopy: Record<Locale, ClinicCopy> = {
  en: {
    servicesTitle: "Clinic services",
    servicesIntro: "Specialist pathways for assessment, treatment, and recovery of hand, wrist, and upper-extremity conditions in a focused private setting.",
    services: [
      { title: "Specialist consultation", text: "Clinical examination, imaging review, diagnosis, and an individualized plan for nerve, tendon, joint, fracture, and overuse conditions." },
      { title: "Office procedures", text: "Selected minor surgery, injections, closed reduction, wound care, casting, and splinting are provided when clinically appropriate." },
      { title: "Follow-up and rehabilitation", text: "Structured follow-up, recovery monitoring, and early rehabilitation guidance connect treatment decisions to safe return of function." },
    ],
    officeTitle: "Dr. Moradi’s private office",
    officeText: [
      "The private office is the principal setting for admission, specialist consultation, selected procedures, and follow-up. Its workflow is designed for privacy, focused examination, and direct communication with the clinical team.",
      "Major or subspecialty operations that require hospital facilities are planned separately at an appropriate equipped center. The treating team confirms the correct setting after clinical assessment.",
    ],
    officeImages: ["Clinic reception and consultation corridor", "Private office architectural details", "Clinical display and waiting area"],
    serviceTypeTitle: "Type of services",
    serviceTypeText: "The clinic combines patient-centered assessment with minimally invasive techniques, microscopic precision, and evidence-based protocols. Care is selected according to the diagnosis, safety requirements, and the facilities needed for each procedure.",
    scopeTitle: "Scope of clinical services",
    scopeItems: [
      "Carpal tunnel release using modern, limited-incision techniques",
      "Trigger finger and tendon-nodule release",
      "Treatment of De Quervain’s tenosynovitis",
      "Treatment of tennis and golfer’s elbow",
      "Reduction and fixation of selected simple hand fractures",
      "Repair of selected superficial nerve and flexor or extensor tendon injuries",
      "Specialized intra-articular and peri-tendinous injections",
      "Advanced wound dressings, casting, and custom splinting",
      "Closed reduction of selected dislocations and simple fractures",
      "Initial rehabilitation education and recovery planning",
    ],
    faqTitle: "Frequently asked questions",
    faqIntro: "General guidance for planning a visit. Individual medical instructions from the treating team always take priority.",
    faqs: [
      { question: "How do I book a planned visit?", answer: "Planned in-person visits are booked only through the approved Nobat.ir profile linked on this website." },
      { question: "What should I bring to the consultation?", answer: "Bring identification, relevant imaging, test reports, previous operation notes, a current medication list, and any splint or device you use." },
      { question: "Can urgent injuries be seen at the office?", answer: "Initial urgent triage is available Saturday, Monday, and Wednesday from 15:45 to 18:30. Life- or limb-threatening injuries require immediate emergency services." },
      { question: "Are all procedures performed in the office?", answer: "No. Only suitable outpatient procedures are performed there. Major or facility-dependent surgery is scheduled at an equipped hospital." },
      { question: "Will I receive rehabilitation instructions?", answer: "When appropriate, the team provides initial exercises, protection guidance, and the timing of formal hand therapy or rehabilitation." },
      { question: "Can a diagnosis be made through the contact form?", answer: "No. The contact form is for professional enquiries and does not replace examination, diagnosis, or emergency care." },
    ],
  },
  fa: {
    servicesTitle: "خدمات کلینیک",
    servicesIntro: "مسیرهای تخصصی ارزیابی، درمان و بازیابی عملکرد بیماری‌ها و آسیب‌های دست، مچ و اندام فوقانی در محیط خصوصی و متمرکز.",
    services: [
      { title: "مشاوره تخصصی", text: "معاینه بالینی، بررسی تصاویر، تشخیص و طراحی برنامه اختصاصی برای بیماری‌های عصب، تاندون، مفصل، شکستگی و آسیب‌های ناشی از کار زیاد." },
      { title: "اقدامات مطب", text: "برخی جراحی‌های محدود، تزریق‌ها، جااندازی بسته، مراقبت زخم، گچ‌گیری و آتل‌بندی در صورت مناسب‌بودن شرایط بالینی انجام می‌شوند." },
      { title: "پیگیری و توان‌بخشی", text: "پیگیری ساختاریافته، پایش روند بهبود و راهنمایی توان‌بخشی زودهنگام، تصمیم درمانی را به بازگشت ایمن عملکرد متصل می‌کند." },
    ],
    officeTitle: "مطب خصوصی دکتر مرادی",
    officeText: [
      "مطب، محل اصلی پذیرش، مشاوره تخصصی، برخی اقدامات درمانی و پیگیری است. گردش کار این فضا برای حفظ حریم خصوصی، معاینه متمرکز و ارتباط مستقیم با تیم درمان طراحی شده است.",
      "جراحی‌های بزرگ یا فوق‌تخصصی که به امکانات بیمارستانی نیاز دارند، پس از ارزیابی بالینی در مرکز مجهز مناسب برنامه‌ریزی می‌شوند و تیم درمان محل صحیح انجام کار را مشخص می‌کند.",
    ],
    officeImages: ["پذیرش و راهروی مشاوره کلینیک", "جزئیات معماری مطب خصوصی", "فضای انتظار و ویترین علمی مطب"],
    serviceTypeTitle: "نوع خدمات",
    serviceTypeText: "کلینیک، ارزیابی بیمارمحور را با تکنیک‌های کم‌تهاجمی، دقت میکروسکوپی و پروتکل‌های مبتنی بر شواهد ترکیب می‌کند. انتخاب خدمت به تشخیص، الزامات ایمنی و امکانات لازم برای هر اقدام وابسته است.",
    scopeTitle: "دامنه خدمات بالینی",
    scopeItems: [
      "آزادسازی تونل کارپ با روش‌های مدرن و برش محدود",
      "درمان انگشت ماشه‌ای و آزادسازی ندول تاندون",
      "درمان تنوسینوویت دکرون",
      "درمان آرنج تنیس‌بازان و گلف‌بازان",
      "جااندازی و تثبیت برخی شکستگی‌های ساده دست",
      "ترمیم منتخب آسیب‌های سطحی عصب و تاندون‌های خم‌کننده یا بازکننده",
      "تزریق‌های تخصصی داخل مفصل و اطراف تاندون",
      "پانسمان پیشرفته، گچ‌گیری و آتل اختصاصی",
      "جااندازی بسته برخی دررفتگی‌ها و شکستگی‌های ساده",
      "آموزش اولیه توان‌بخشی و برنامه‌ریزی دوران بهبود",
    ],
    faqTitle: "سوالات متداول",
    faqIntro: "راهنمای عمومی برای برنامه‌ریزی مراجعه؛ دستور اختصاصی تیم درمان همیشه در اولویت است.",
    faqs: [
      { question: "برای ویزیت برنامه‌ریزی‌شده چگونه نوبت بگیرم؟", answer: "نوبت حضوری برنامه‌ریزی‌شده فقط از پروفایل تأییدشده Nobat.ir که در این سایت لینک شده است ثبت می‌شود." },
      { question: "برای مشاوره چه مدارکی همراه داشته باشم؟", answer: "مدرک شناسایی، تصاویر پزشکی، گزارش آزمایش‌ها، شرح عمل‌های قبلی، فهرست داروها و هر آتل یا وسیله‌ای را که استفاده می‌کنید همراه بیاورید." },
      { question: "آسیب‌های فوری در مطب بررسی می‌شوند؟", answer: "تریاژ اولیه فوری شنبه، دوشنبه و چهارشنبه از ساعت ۱۵:۴۵ تا ۱۸:۳۰ انجام می‌شود. در شرایط تهدیدکننده جان یا اندام فوراً به اورژانس مراجعه کنید." },
      { question: "آیا همه اقدامات در مطب انجام می‌شوند؟", answer: "خیر. فقط اقدامات سرپایی مناسب در مطب انجام می‌شوند و جراحی‌های بزرگ یا وابسته به تجهیزات در بیمارستان مجهز برنامه‌ریزی خواهند شد." },
      { question: "آیا راهنمای توان‌بخشی دریافت می‌کنم؟", answer: "در صورت نیاز، تیم درمان تمرین‌های اولیه، روش محافظت و زمان شروع هندتراپی یا توان‌بخشی رسمی را توضیح می‌دهد." },
      { question: "آیا از طریق فرم تماس تشخیص پزشکی داده می‌شود؟", answer: "خیر. فرم تماس برای مکاتبات حرفه‌ای است و جایگزین معاینه، تشخیص یا خدمات اورژانس نیست." },
    ],
  },
  ar: {
    servicesTitle: "خدمات العيادة",
    servicesIntro: "مسارات متخصصة لتقييم وعلاج واستعادة وظيفة اليد والمعصم والطرف العلوي في بيئة خاصة ومركزة.",
    services: [
      { title: "استشارة اختصاصية", text: "فحص سريري ومراجعة الصور والتشخيص ووضع خطة فردية لحالات الأعصاب والأوتار والمفاصل والكسور وإصابات الإجهاد." },
      { title: "إجراءات العيادة", text: "تُجرى جراحات صغرى وحقن وردّ مغلق وعناية بالجروح وجبائر وتثبيت مختار عندما تكون مناسبة سريرياً." },
      { title: "المتابعة والتأهيل", text: "تربط المتابعة المنظمة ومراقبة التعافي وإرشادات التأهيل المبكر قرارات العلاج بالعودة الآمنة إلى الوظيفة." },
    ],
    officeTitle: "العيادة الخاصة للدكتور مرادي",
    officeText: [
      "العيادة الخاصة هي المكان الرئيسي للاستقبال والاستشارة الاختصاصية والإجراءات المختارة والمتابعة. صُمم سير العمل فيها للخصوصية والفحص المركز والتواصل المباشر مع الفريق الطبي.",
      "تُخطط العمليات الكبرى أو التخصصية التي تحتاج إلى مرافق مستشفى بصورة منفصلة في مركز مجهز مناسب، ويحدد الفريق المعالج المكان الصحيح بعد التقييم السريري.",
    ],
    officeImages: ["الاستقبال وممر الاستشارة", "تفاصيل تصميم العيادة الخاصة", "منطقة الانتظار والعرض العلمي"],
    serviceTypeTitle: "نوع الخدمات",
    serviceTypeText: "تجمع العيادة بين التقييم المتمحور حول المريض والتقنيات محدودة التدخل والدقة المجهرية والبروتوكولات القائمة على الدليل. يُختار المسار بحسب التشخيص ومتطلبات السلامة والتجهيزات اللازمة لكل إجراء.",
    scopeTitle: "نطاق الخدمات السريرية",
    scopeItems: [
      "تحرير النفق الرسغي بتقنيات حديثة وشق محدود",
      "علاج الإصبع الزنادي وتحرير عقدة الوتر",
      "علاج التهاب غمد وتر دي كيرفان",
      "علاج مرفق لاعب التنس والغولف",
      "رد وتثبيت كسور اليد البسيطة المختارة",
      "إصلاح إصابات سطحية مختارة للأعصاب وأوتار الثني أو البسط",
      "حقن تخصصية داخل المفصل وحول الأوتار",
      "ضمادات متقدمة وجبائر وتثبيت مخصص",
      "الرد المغلق لخلوع وكسور بسيطة مختارة",
      "التثقيف الأولي للتأهيل وتخطيط التعافي",
    ],
    faqTitle: "الأسئلة الشائعة",
    faqIntro: "إرشادات عامة لتخطيط الزيارة، وتبقى تعليمات الفريق المعالج الفردية هي الأولوية.",
    faqs: [
      { question: "كيف أحجز زيارة مخططة؟", answer: "تُحجز الزيارات الحضورية المخططة فقط عبر ملف Nobat.ir المعتمد المرتبط بهذا الموقع." },
      { question: "ما الذي أحضره إلى الاستشارة؟", answer: "أحضر الهوية والصور والتقارير الطبية وتقارير العمليات السابقة وقائمة الأدوية وأي جبيرة أو جهاز تستخدمه." },
      { question: "هل تُفحص الإصابات العاجلة في العيادة؟", answer: "يتاح الفرز العاجل الأولي أيام السبت والاثنين والأربعاء من ١٥:٤٥ إلى ١٨:٣٠. الحالات المهددة للحياة أو الطرف تحتاج إلى الطوارئ فوراً." },
      { question: "هل تُجرى كل الإجراءات في العيادة؟", answer: "لا. تُجرى الإجراءات الخارجية المناسبة فقط، أما الجراحة الكبرى أو المعتمدة على تجهيزات خاصة فتُحدد في مستشفى مجهز." },
      { question: "هل أتلقى تعليمات للتأهيل؟", answer: "عند الحاجة يشرح الفريق التمارين الأولية وطرق الحماية وتوقيت بدء علاج اليد أو التأهيل الرسمي." },
      { question: "هل يمكن الحصول على تشخيص عبر نموذج الاتصال؟", answer: "لا. النموذج للاستفسارات المهنية ولا يحل محل الفحص أو التشخيص أو خدمات الطوارئ." },
    ],
  },
};

export const pageCoverImages: Record<string, string> = {
  "clinical-care": "/media/pages/clinic-cover.jpg",
  innovation: "/media/innovation/external-fixator.jpg",
  research: "/media/innovation/bionic-hand.png",
  education: "/media/hero/hero-bg-v2.jpg",
  about: "/media/edited/dr-moradi-hero-v2.jpg",
  blog: "/media/news/best-paper-meeting.jpg",
  contact: "/media/pages/clinic-cover.jpg",
};
