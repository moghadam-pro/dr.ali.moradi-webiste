import type { InteriorPageData, Locale } from "./site-content";

export type AboutPageCopy = InteriorPageData & {
  storyKicker: string;
  storyTitle: string;
  storyText: string[];
  credentials: string[];
  practiceKicker: string;
  practiceTitle: string;
  practiceText: string;
  principles: { title: string; text: string }[];
  journeyKicker: string;
  journeyTitle: string;
  journeyText: string;
  timeline: { years: string; title: string; text: string }[];
  ecosystemKicker: string;
  ecosystemTitle: string;
  ecosystemText: string;
  ecosystem: { slug: string; title: string; text: string; image: string }[];
  recognitionKicker: string;
  recognitionTitle: string;
  recognitionText: string;
  recognitionAction: string;
};

export const aboutPageCopy: Record<Locale, AboutPageCopy> = {
  en: {
    kicker: "ABOUT DR. MORADI",
    title: "A surgeon shaped by curiosity, evidence, and making.",
    intro: "Ali Moradi, MD, PhD, is a hand and upper-extremity surgeon, Associate Professor of Orthopedics, researcher, and medical innovator in Mashhad.",
    sections: [],
    ctaTitle: "Choose the right path for your visit",
    ctaText: "Clinical assessment, research collaboration, and innovation enquiries each have a dedicated route.",
    storyKicker: "THE PERSON BEHIND THE PRACTICE",
    storyTitle: "Clinical care begins with listening—and improves through disciplined questioning.",
    storyText: [
      "Dr. Ali Moradi works at the intersection of hand surgery, upper-extremity reconstruction, academic research, and medical-device development. His practice is built around understanding what a patient needs to return to: work, independence, sport, or the ordinary movements that make daily life possible.",
      "Alongside clinical work, he develops questions from real surgical problems and follows them through biomechanics, prototyping, validation, and education. That connected approach keeps innovation accountable to anatomy, evidence, safety, and practical patient benefit.",
    ],
    credentials: ["Hand & upper-extremity surgeon", "Associate Professor of Orthopedics", "MD, PhD in Orthotics & Prosthetics"],
    practiceKicker: "A CONNECTED PRACTICE",
    practiceTitle: "One clinical problem can become a research question, a teaching case, and a better tool.",
    practiceText: "This is why clinical care, research, education, and innovation are not presented as separate careers. They are four views of the same responsibility: to understand function precisely and improve how it is restored.",
    principles: [
      { title: "Patient-first decisions", text: "Treatment is selected around diagnosis, safety, function, expectations, and the realities of recovery—not around a single technique." },
      { title: "Evidence with context", text: "Published evidence, clinical examination, imaging, and the patient’s priorities are interpreted together." },
      { title: "Ideas made testable", text: "Engineering concepts are translated into prototypes and measurable studies before they are treated as solutions." },
    ],
    journeyKicker: "TRAINING & LEADERSHIP",
    journeyTitle: "A path from orthopedic training to hand surgery, prosthetics, and medical robotics.",
    journeyText: "The timeline below is a concise editorial view of the detailed curriculum vitae. Short courses and fellowships remain identified accurately rather than being compressed into a single credential.",
    timeline: [
      { years: "1998–2009", title: "Medicine and orthopedic surgery", text: "Medical training and orthopedic-surgery residency at Mashhad University of Medical Sciences." },
      { years: "2013–2015", title: "Hand and upper-extremity research", text: "Clinical Research Fellowship at Massachusetts General Hospital, focused on hand and upper-extremity research." },
      { years: "2015–2019", title: "Orthotics and prosthetics", text: "PhD work at MUMS connecting biomechanics, prosthetic systems, and functional reconstruction." },
      { years: "2018", title: "Advanced hand and wrist training", text: "Clinical Fellowship in Hand and Microsurgery at Tehran University of Medical Sciences and wrist-arthroscopy training at Clinique Bizet in Paris." },
      { years: "Today", title: "Clinical and research leadership", text: "Associate Professor of Orthopedics, leadership in orthopedic research and the Bone and Joint Research Laboratory, medical robotics, and Avisa medical innovation." },
    ],
    ecosystemKicker: "FIELDS OF WORK",
    ecosystemTitle: "Care, evidence, and invention—designed to stay connected.",
    ecosystemText: "Explore the three areas that organize Dr. Moradi’s current clinical and academic work.",
    ecosystem: [
      { slug: "research", title: "Research", text: "Hand and wrist outcomes, biomechanics, neuropathy, prosthetic control, registries, and rehabilitation technology.", image: "/media/pages/research-cover.jpg" },
      { slug: "innovation", title: "Innovation", text: "External fixation, magnetic distraction, bionic-hand interfaces, implant concepts, and clinically grounded prototyping.", image: "/media/pages/innovation-cover.jpg" },
      { slug: "education", title: "Education", text: "Teaching that connects anatomy and surgical reasoning with evidence, engineering, and responsible clinical decisions.", image: "/media/pages/education-cover.jpg" },
    ],
    recognitionKicker: "RECOGNITION",
    recognitionTitle: "The work is measured by contribution, not display.",
    recognitionText: "Selected awards, certificates, patents, publications, and invited academic activities are documented in the unified Blog and News archive. The archive is curated from approved public material and will continue to grow as records are verified.",
    recognitionAction: "Explore awards and stories",
  },
  fa: {
    kicker: "درباره دکتر مرادی",
    title: "جراحی که مسیرش را کنجکاوی، شواهد و ساختن شکل داده است.",
    intro: "دکتر علی مرادی، پزشک و دارای دکتری تخصصی، جراح دست و اندام فوقانی، دانشیار ارتوپدی، پژوهشگر و نوآور تجهیزات پزشکی در مشهد است.",
    sections: [],
    ctaTitle: "مسیر مناسب مراجعه خود را انتخاب کنید",
    ctaText: "برای ارزیابی بالینی، همکاری پژوهشی و گفت‌وگو درباره نوآوری مسیرهای جداگانه در نظر گرفته شده است.",
    storyKicker: "انسان پشت این مسیر حرفه‌ای",
    storyTitle: "مراقبت بالینی با شنیدن آغاز می‌شود و با پرسشگری منظم بهتر می‌شود.",
    storyText: [
      "فعالیت دکتر علی مرادی در نقطه پیوند جراحی دست، بازسازی اندام فوقانی، پژوهش دانشگاهی و توسعه تجهیزات پزشکی قرار دارد. مبنای درمان برای او فهمیدن مقصد واقعی بیمار است: بازگشت به کار، استقلال، ورزش یا همان حرکت‌های ساده‌ای که زندگی روزمره را ممکن می‌کنند.",
      "در کنار درمان، مسئله‌های واقعی اتاق عمل به پرسش پژوهشی تبدیل می‌شوند و از مسیر بیومکانیک، نمونه‌سازی، اعتبارسنجی و آموزش پیش می‌روند. این نگاه پیوسته، نوآوری را به آناتومی، شواهد، ایمنی و منفعت عملی بیمار پاسخ‌گو نگه می‌دارد.",
    ],
    credentials: ["جراح دست و اندام فوقانی", "دانشیار ارتوپدی", "پزشک و دکتری تخصصی ارتز و پروتز"],
    practiceKicker: "یک مسیر به‌هم‌پیوسته",
    practiceTitle: "یک مسئله بالینی می‌تواند به پرسش پژوهشی، موضوع آموزش و ابزار بهتر تبدیل شود.",
    practiceText: "به همین دلیل درمان، پژوهش، آموزش و نوآوری چهار فعالیت جداگانه نیستند؛ چهار زاویه از یک مسئولیت‌اند: شناخت دقیق عملکرد و بهتر کردن راه بازگرداندن آن.",
    principles: [
      { title: "تصمیم بیمارمحور", text: "انتخاب درمان بر پایه تشخیص، ایمنی، عملکرد، انتظار بیمار و واقعیت دوره بهبود انجام می‌شود، نه وابستگی به یک تکنیک." },
      { title: "شواهد در بستر واقعی", text: "مقاله‌ها، معاینه بالینی، تصویربرداری و اولویت‌های بیمار در کنار یکدیگر تفسیر می‌شوند." },
      { title: "ایده‌های قابل آزمون", text: "مفهوم مهندسی ابتدا به نمونه و مطالعه قابل اندازه‌گیری تبدیل می‌شود و سپس می‌تواند به‌عنوان راه‌حل مطرح شود." },
    ],
    journeyKicker: "آموزش و مسئولیت علمی",
    journeyTitle: "مسیری از آموزش ارتوپدی تا جراحی دست، پروتز و رباتیک پزشکی.",
    journeyText: "این خط زمانی روایتی کوتاه از رزومه تفصیلی است. دوره‌های کوتاه و فلوشیپ‌ها با عنوان دقیق خود معرفی شده‌اند و در یک عنوان گمراه‌کننده ادغام نشده‌اند.",
    timeline: [
      { years: "۱۳۷۷–۱۳۸۸", title: "پزشکی و جراحی ارتوپدی", text: "تحصیل پزشکی و دوره تخصص جراحی ارتوپدی در دانشگاه علوم پزشکی مشهد." },
      { years: "۱۳۹۲–۱۳۹۴", title: "پژوهش دست و اندام فوقانی", text: "فلوشیپ پژوهش بالینی دست و اندام فوقانی در بیمارستان عمومی ماساچوست." },
      { years: "۱۳۹۴–۱۳۹۸", title: "ارتز و پروتز", text: "دکتری تخصصی در دانشگاه علوم پزشکی مشهد با پیوند بیومکانیک، سامانه‌های پروتزی و بازسازی عملکرد." },
      { years: "۱۳۹۷", title: "آموزش تکمیلی دست و مچ", text: "فلوشیپ بالینی جراحی دست و میکروسکوپی در دانشگاه علوم پزشکی تهران و آموزش آرتروسکوپی مچ در کلینیک بیزه پاریس." },
      { years: "امروز", title: "رهبری بالینی و پژوهشی", text: "دانشیار ارتوپدی و فعال در هدایت مرکز تحقیقات ارتوپدی، آزمایشگاه تحقیقات استخوان و مفصل، رباتیک پزشکی و نوآوری تجهیزات آویسا." },
    ],
    ecosystemKicker: "حوزه‌های فعالیت",
    ecosystemTitle: "درمان، شواهد و اختراع؛ با یک ارتباط واقعی.",
    ecosystemText: "سه حوزه‌ای را ببینید که فعالیت بالینی و دانشگاهی امروز دکتر مرادی را سازمان می‌دهند.",
    ecosystem: [
      { slug: "research", title: "پژوهش", text: "پیامدهای جراحی دست و مچ، بیومکانیک، نوروپاتی، کنترل پروتز، رجیستری و فناوری توان‌بخشی.", image: "/media/pages/research-cover.jpg" },
      { slug: "innovation", title: "نوآوری", text: "فیکساتور خارجی، دیستراکشن مغناطیسی، رابط دست بیونیک، ایده‌های ایمپلنت و نمونه‌سازی بالینی.", image: "/media/pages/innovation-cover.jpg" },
      { slug: "education", title: "آموزش", text: "پیوند آناتومی و استدلال جراحی با شواهد، مهندسی و تصمیم‌گیری مسئولانه بالینی.", image: "/media/pages/education-cover.jpg" },
    ],
    recognitionKicker: "افتخارات",
    recognitionTitle: "ارزش کار با میزان اثر آن سنجیده می‌شود، نه با نمایش آن.",
    recognitionText: "منتخبی از جوایز، گواهی‌ها، اختراع‌ها، مقاله‌ها و فعالیت‌های علمی دعوت‌شده در آرشیو یکپارچه وبلاگ و اخبار ثبت شده است. این آرشیو بر پایه منابع عمومی تأییدشده تنظیم شده و با راستی‌آزمایی اسناد تکمیل می‌شود.",
    recognitionAction: "مشاهده افتخارات و روایت‌ها",
  },
  ar: {
    kicker: "عن الدكتور مرادي",
    title: "جرّاح صاغ مسيرَه الفضولُ والدليلُ وصناعةُ الحلول.",
    intro: "الدكتور علي مرادي، طبيب وحاصل على الدكتوراه، جرّاح اليد والطرف العلوي وأستاذ مشارك في جراحة العظام وباحث ومبتكر طبي في مشهد.",
    sections: [], ctaTitle: "اختر المسار المناسب لزيارتك", ctaText: "للتقييم السريري والتعاون البحثي واستفسارات الابتكار مسارات مخصصة.",
    storyKicker: "الإنسان وراء الممارسة", storyTitle: "تبدأ الرعاية السريرية بالاستماع وتتحسن بالسؤال المنهجي.",
    storyText: ["يعمل الدكتور علي مرادي عند تقاطع جراحة اليد وإعادة بناء الطرف العلوي والبحث الأكاديمي وتطوير الأجهزة الطبية. وتنطلق ممارسته من فهم ما يحتاج المريض إلى استعادته: العمل أو الاستقلال أو الرياضة أو حركات الحياة اليومية.", "إلى جانب العمل السريري، يحوّل مشكلات الجراحة الواقعية إلى أسئلة بحثية ويمضي بها عبر الميكانيكا الحيوية والنمذجة والتحقق والتعليم، لتظل الابتكارات مسؤولة أمام التشريح والدليل والسلامة وفائدة المريض."],
    credentials: ["جرّاح اليد والطرف العلوي", "أستاذ مشارك في جراحة العظام", "طبيب ودكتوراه في الأطراف الاصطناعية والأجهزة التقويمية"],
    practiceKicker: "ممارسة مترابطة", practiceTitle: "قد تصبح المشكلة السريرية سؤالاً بحثياً وحالة تعليمية وأداة أفضل.", practiceText: "الرعاية والبحث والتعليم والابتكار ليست مسارات منفصلة، بل أربع زوايا لمسؤولية واحدة: فهم الوظيفة بدقة وتحسين طريقة استعادتها.",
    principles: [{ title: "قرار متمحور حول المريض", text: "يُختار العلاج وفق التشخيص والسلامة والوظيفة والتوقعات وواقع التعافي." }, { title: "الدليل ضمن سياقه", text: "تُقرأ الدراسات والفحص والصور وأولويات المريض معاً." }, { title: "أفكار قابلة للاختبار", text: "تتحول الفكرة الهندسية إلى نموذج ودراسة قابلة للقياس قبل اعتبارها حلاً." }],
    journeyKicker: "التدريب والقيادة", journeyTitle: "مسار من تدريب العظام إلى جراحة اليد والأطراف الاصطناعية والروبوتات الطبية.", journeyText: "خط زمني موجز للسيرة التفصيلية يحافظ على التسمية الدقيقة للدورات والزمالات.",
    timeline: [{ years: "1998–2009", title: "الطب وجراحة العظام", text: "الطب والإقامة في جراحة العظام بجامعة مشهد للعلوم الطبية." }, { years: "2013–2015", title: "بحث اليد والطرف العلوي", text: "زمالة بحث سريري في مستشفى ماساتشوستس العام." }, { years: "2015–2019", title: "الأجهزة التقويمية والأطراف الاصطناعية", text: "دكتوراه تربط الميكانيكا الحيوية والمنظومات التعويضية واستعادة الوظيفة." }, { years: "2018", title: "تدريب متقدم لليد والمعصم", text: "زمالة سريرية في جراحة اليد والجراحة المجهرية بطهران وتدريب تنظير المعصم في باريس." }, { years: "اليوم", title: "قيادة سريرية وبحثية", text: "أستاذ مشارك وقيادة في بحوث العظام ومختبر العظم والمفصل والروبوتات الطبية وابتكار أجهزة آویسا." }],
    ecosystemKicker: "مجالات العمل", ecosystemTitle: "الرعاية والدليل والابتكار—في منظومة واحدة.", ecosystemText: "استكشف المجالات الثلاثة التي تنظم عمل الدكتور مرادي السريري والأكاديمي.",
    ecosystem: [{ slug: "research", title: "البحث", text: "نتائج جراحة اليد والمعصم والميكانيكا الحيوية والاعتلال العصبي والتحكم التعويضي وتقنيات التأهيل.", image: "/media/pages/research-cover.jpg" }, { slug: "innovation", title: "الابتكار", text: "التثبيت الخارجي والتشتيت المغناطيسي وواجهات اليد الإلكترونية والنماذج السريرية.", image: "/media/pages/innovation-cover.jpg" }, { slug: "education", title: "التعليم", text: "ربط التشريح والتفكير الجراحي بالدليل والهندسة والقرار السريري المسؤول.", image: "/media/pages/education-cover.jpg" }],
    recognitionKicker: "التقدير", recognitionTitle: "تُقاس قيمة العمل بأثره لا بعرضه.", recognitionText: "تُوثق مجموعة مختارة من الجوائز والشهادات والاختراعات والمنشورات والأنشطة الأكاديمية في أرشيف المدونة والأخبار، اعتماداً على المواد العامة المعتمدة.", recognitionAction: "استكشف الجوائز والقصص",
  },
};
