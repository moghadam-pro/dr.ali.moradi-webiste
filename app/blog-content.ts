import type { Locale } from "./site-content";

type LocalizedText = Record<Locale, string>;

export type ContentTag = "blog" | "news" | "innovation" | "research" | "care" | "recovery";

export type ContentPost = {
  slug: string;
  image: string;
  date: string;
  readMinutes: number;
  category: LocalizedText;
  tags: ContentTag[];
  title: LocalizedText;
  excerpt: LocalizedText;
};

export type BlogPost = ContentPost;

const images = [
  "/media/news/best-paper-meeting.jpg",
  "/media/news/top-cited.jpg",
  "/media/news/congress-recognition.jpg",
  "/media/innovation/external-fixator.jpg",
  "/media/innovation/bionic-hand.png",
  "/media/innovation/magnetic-distractor.png",
];

const categories = {
  care: { en: "Clinical education", fa: "آموزش درمانی", ar: "تثقيف سريري" },
  recovery: { en: "Recovery", fa: "دوران بهبود", ar: "التعافي" },
  innovation: { en: "Innovation", fa: "نوآوری", ar: "الابتكار" },
  research: { en: "Research literacy", fa: "سواد پژوهشی", ar: "الثقافة البحثية" },
} satisfies Record<string, LocalizedText>;

const entries: Array<{
  slug: string;
  category: keyof typeof categories;
  tags?: ContentTag[];
  image?: string;
  title: LocalizedText;
  excerpt: LocalizedText;
}> = [
  {
    slug: "understanding-carpal-tunnel-syndrome", category: "care",
    title: { en: "Understanding carpal tunnel syndrome", fa: "آشنایی با سندرم تونل کارپ", ar: "فهم متلازمة النفق الرسغي" },
    excerpt: { en: "Numbness, night symptoms, and loss of grip may reflect pressure on the median nerve; careful examination helps distinguish severity and suitable treatment.", fa: "بی‌حسی، علائم شبانه و کاهش قدرت گرفتن می‌تواند ناشی از فشار بر عصب مدین باشد؛ معاینه دقیق شدت و مسیر درمان مناسب را روشن می‌کند.", ar: "قد يشير الخدر والأعراض الليلية وضعف القبضة إلى ضغط على العصب المتوسط، ويساعد الفحص الدقيق في تحديد الشدة والعلاج المناسب." },
  },
  {
    slug: "trigger-finger-why-it-locks", category: "care",
    title: { en: "Trigger finger: why a finger locks", fa: "انگشت ماشه‌ای؛ چرا انگشت قفل می‌شود؟", ar: "الإصبع الزنادي: لماذا ينغلق الإصبع؟" },
    excerpt: { en: "A narrowed tendon tunnel can cause clicking, pain, or locking; treatment depends on duration, examination findings, and functional limitation.", fa: "تنگ‌شدن مسیر تاندون می‌تواند باعث صدا، درد یا قفل‌شدن انگشت شود؛ درمان به مدت علائم، معاینه و محدودیت عملکرد بستگی دارد.", ar: "قد يسبب تضيق نفق الوتر طقطقة أو ألماً أو انغلاقاً، ويعتمد العلاج على مدة الأعراض ونتائج الفحص والحد الوظيفي." },
  },
  {
    slug: "de-quervain-wrist-pain", category: "care",
    title: { en: "De Quervain’s and pain on the thumb side of the wrist", fa: "دکرون و درد سمت شست مچ", ar: "دي كيرفان وألم جهة الإبهام من المعصم" },
    excerpt: { en: "Pain near the thumb-side wrist may involve irritated tendon sheaths, but examination is important because several conditions can feel similar.", fa: "درد کنار شست مچ ممکن است از تحریک غلاف تاندون باشد، اما معاینه اهمیت دارد چون چند بیماری می‌توانند علائم مشابه ایجاد کنند.", ar: "قد يرتبط الألم قرب جهة الإبهام بالتهاب أغمدة الأوتار، لكن الفحص مهم لأن حالات متعددة قد تبدو متشابهة." },
  },
  {
    slug: "tennis-and-golfers-elbow", category: "care",
    title: { en: "Tennis elbow and golfer’s elbow", fa: "آرنج تنیس‌بازان و گلف‌بازان", ar: "مرفق لاعب التنس والغولف" },
    excerpt: { en: "Repetitive loading can irritate tendon attachments around the elbow; activity analysis and progressive rehabilitation are central to most care plans.", fa: "بارگذاری تکراری می‌تواند محل اتصال تاندون‌های آرنج را تحریک کند؛ بررسی فعالیت و توان‌بخشی تدریجی محور بیشتر برنامه‌های درمان است.", ar: "قد يهيج التحميل المتكرر ارتكازات الأوتار حول المرفق، ويعد تحليل النشاط والتأهيل التدريجي محور معظم خطط العلاج." },
  },
  {
    slug: "distal-radius-fracture-basics", category: "care",
    title: { en: "Distal radius fractures: the first decisions", fa: "شکستگی دیستال رادیوس؛ تصمیم‌های نخست", ar: "كسور الكعبرة البعيدة: القرارات الأولى" },
    excerpt: { en: "Alignment, stability, joint involvement, age, and activity needs guide whether a wrist fracture requires casting, reduction, or surgical fixation.", fa: "راستای استخوان، پایداری، درگیری مفصل، سن و نیازهای فعالیت مشخص می‌کند شکستگی مچ به گچ، جااندازی یا تثبیت جراحی نیاز دارد.", ar: "يحدد المحاذاة والثبات وامتداد الكسر للمفصل والعمر والحاجة الوظيفية ما إذا كان العلاج بالجبس أو الرد أو التثبيت الجراحي." },
  },
  {
    slug: "preparing-for-hand-surgery", category: "recovery",
    title: { en: "Preparing for hand surgery", fa: "آمادگی برای جراحی دست", ar: "الاستعداد لجراحة اليد" },
    excerpt: { en: "Medication review, fasting instructions, transport planning, clean skin, and a clear recovery plan make the day of surgery safer and less stressful.", fa: "مرور داروها، رعایت دستور ناشتا، برنامه رفت‌وآمد، پوست تمیز و برنامه روشن بهبود، روز جراحی را ایمن‌تر و کم‌تنش‌تر می‌کند.", ar: "تساعد مراجعة الأدوية وتعليمات الصيام وترتيب النقل ونظافة الجلد وخطة التعافي الواضحة في جعل يوم الجراحة أكثر أماناً." },
  },
  {
    slug: "wound-care-after-hand-surgery", category: "recovery",
    title: { en: "Wound care after hand surgery", fa: "مراقبت زخم پس از جراحی دست", ar: "العناية بالجرح بعد جراحة اليد" },
    excerpt: { en: "Keeping dressings protected, controlling swelling, and recognizing warning signs support healing; the surgeon’s specific instructions always take priority.", fa: "محافظت از پانسمان، کنترل تورم و شناخت علائم هشدار به ترمیم کمک می‌کند؛ دستور اختصاصی جراح همیشه در اولویت است.", ar: "حماية الضماد وضبط التورم ومعرفة علامات التحذير تدعم الالتئام، وتبقى تعليمات الجراح الخاصة هي الأولوية." },
  },
  {
    slug: "hand-therapy-and-early-motion", category: "recovery",
    title: { en: "Hand therapy and the timing of early motion", fa: "هندتراپی و زمان حرکت زودهنگام", ar: "علاج اليد وتوقيت الحركة المبكرة" },
    excerpt: { en: "Movement can protect function, but starting too early or too late may be harmful; timing depends on the injured tissue and repair strength.", fa: "حرکت می‌تواند از عملکرد محافظت کند، اما شروع بسیار زود یا دیر ممکن است آسیب‌زا باشد؛ زمان‌بندی به بافت آسیب‌دیده و استحکام ترمیم بستگی دارد.", ar: "قد تحمي الحركة الوظيفة، لكن البدء المبكر جداً أو المتأخر قد يضر؛ يعتمد التوقيت على النسيج المصاب وقوة الإصلاح." },
  },
  {
    slug: "how-peripheral-nerves-recover", category: "recovery",
    title: { en: "How peripheral nerves recover", fa: "عصب‌های محیطی چگونه بهبود می‌یابند؟", ar: "كيف تتعافى الأعصاب الطرفية؟" },
    excerpt: { en: "Nerve recovery is gradual and depends on injury level, repair quality, distance to the target muscle or skin, and structured follow-up.", fa: "بهبود عصب تدریجی است و به سطح آسیب، کیفیت ترمیم، فاصله تا عضله یا پوست هدف و پیگیری منظم بستگی دارد.", ar: "تعافي العصب تدريجي ويعتمد على مستوى الإصابة وجودة الإصلاح والمسافة إلى العضلة أو الجلد المستهدف والمتابعة المنظمة." },
  },
  {
    slug: "tendon-repair-and-protection", category: "recovery",
    title: { en: "Tendon repair: motion with protection", fa: "ترمیم تاندون؛ حرکت همراه با محافظت", ar: "إصلاح الوتر: حركة مع الحماية" },
    excerpt: { en: "A repaired tendon needs enough protection to heal and enough guided motion to reduce adhesions; rehabilitation protocols balance both demands.", fa: "تاندون ترمیم‌شده برای جوش‌خوردن به محافظت و برای کاهش چسبندگی به حرکت هدایت‌شده نیاز دارد؛ پروتکل توان‌بخشی این دو را متعادل می‌کند.", ar: "يحتاج الوتر المصلح إلى الحماية للالتئام وإلى حركة موجهة لتقليل الالتصاقات، وتوازن بروتوكولات التأهيل بينهما." },
  },
  {
    slug: "microsurgery-in-upper-extremity-care", category: "innovation",
    title: { en: "Microsurgery in upper-extremity care", fa: "میکروسرجری در درمان اندام فوقانی", ar: "الجراحة المجهرية في رعاية الطرف العلوي" },
    excerpt: { en: "Magnification and fine instruments allow repair of small nerves and vessels, supporting selected reconstruction, replantation, and tissue-transfer procedures.", fa: "بزرگ‌نمایی و ابزار ظریف امکان ترمیم عصب‌ها و عروق کوچک را فراهم می‌کند و در بازسازی، پیوند مجدد و انتقال بافت منتخب کاربرد دارد.", ar: "يتيح التكبير والأدوات الدقيقة إصلاح الأعصاب والأوعية الصغيرة لدعم عمليات إعادة البناء وإعادة الزرع ونقل الأنسجة المختارة." },
  },
  {
    slug: "external-fixation-explained", category: "innovation",
    title: { en: "External fixation explained", fa: "فیکساتور خارجی چگونه کار می‌کند؟", ar: "شرح التثبيت الخارجي" },
    excerpt: { en: "External frames can stabilize bone while preserving access to injured soft tissue; configuration is selected according to anatomy and treatment goals.", fa: "فریم خارجی می‌تواند استخوان را پایدار کند و هم‌زمان دسترسی به بافت نرم آسیب‌دیده را حفظ کند؛ آرایش آن براساس آناتومی و هدف درمان انتخاب می‌شود.", ar: "يمكن للإطار الخارجي تثبيت العظم مع الحفاظ على الوصول إلى الأنسجة الرخوة، ويُختار تركيبه وفق التشريح وأهداف العلاج." },
  },
  {
    slug: "myoelectric-and-bionic-hands", category: "innovation", tags: ["innovation"],
    title: { en: "Myoelectric and bionic hands", fa: "دست‌های مایوالکتریک و بیونیک", ar: "الأيدي العضلية الكهربائية والإلكترونية" },
    excerpt: { en: "Prosthetic hands translate biological signals into device commands; comfort, control reliability, training, and task needs shape real-world success.", fa: "دست پروتزی سیگنال‌های زیستی را به فرمان دستگاه تبدیل می‌کند؛ راحتی، پایداری کنترل، آموزش و نیازهای واقعی موفقیت آن را مشخص می‌کند.", ar: "تحول اليد التعويضية الإشارات الحيوية إلى أوامر، ويحدد الراحة وموثوقية التحكم والتدريب واحتياجات المهام نجاحها العملي." },
  },
  {
    slug: "magnetic-sensing-for-prosthetic-control", category: "innovation", tags: ["innovation"],
    title: { en: "Magnetic sensing for prosthetic control", fa: "حسگر مغناطیسی برای کنترل پروتز", ar: "الاستشعار المغناطيسي للتحكم بالطرف التعويضي" },
    excerpt: { en: "Research explores whether implanted magnetic elements and external sensors can create stable, intuitive commands for advanced upper-limb prostheses.", fa: "پژوهش بررسی می‌کند آیا اجزای مغناطیسی کاشتنی و حسگرهای خارجی می‌توانند فرمانی پایدار و طبیعی برای پروتز پیشرفته ایجاد کنند.", ar: "يبحث العمل فيما إذا كانت العناصر المغناطيسية المزروعة والحساسات الخارجية قادرة على توفير أوامر ثابتة وطبيعية للأطراف المتقدمة." },
  },
  {
    slug: "rehabilitation-robotics", category: "innovation", tags: ["innovation"],
    title: { en: "Rehabilitation robotics and assisted practice", fa: "رباتیک توان‌بخشی و تمرین کمکی", ar: "روبوتات التأهيل والتدريب المساعد" },
    excerpt: { en: "Robotic and exoskeleton systems can deliver repeatable assisted movement, but meaningful benefit depends on patient selection and clinical integration.", fa: "سامانه‌های رباتیک و اسکلت بیرونی می‌توانند حرکت کمکی تکرارپذیر ایجاد کنند، اما فایده واقعی به انتخاب بیمار و ادغام درست در درمان بستگی دارد.", ar: "توفر الروبوتات والهياكل الخارجية حركة مساعدة قابلة للتكرار، لكن فائدتها تعتمد على اختيار المريض ودمجها الصحيح في الرعاية." },
  },
  {
    slug: "reading-clinical-research", category: "research", tags: ["research"],
    title: { en: "How to read clinical research with care", fa: "چگونه پژوهش بالینی را دقیق بخوانیم؟", ar: "كيف نقرأ البحث السريري بعناية؟" },
    excerpt: { en: "Study design, comparison groups, outcome definitions, follow-up, and uncertainty matter more than a headline when judging evidence for care.", fa: "طراحی مطالعه، گروه مقایسه، تعریف پیامد، مدت پیگیری و عدم‌قطعیت برای سنجش شواهد از تیتر خبر مهم‌تر هستند.", ar: "تصميم الدراسة ومجموعات المقارنة وتعريف النتائج والمتابعة وعدم اليقين أهم من العنوان عند تقييم دليل الرعاية." },
  },
  {
    slug: "warning-signs-after-hand-injury", category: "care",
    title: { en: "Warning signs after a hand injury", fa: "علائم هشدار پس از آسیب دست", ar: "علامات التحذير بعد إصابة اليد" },
    excerpt: { en: "Severe pain, altered color, loss of feeling, uncontrolled bleeding, major deformity, or inability to move requires prompt professional assessment.", fa: "درد شدید، تغییر رنگ، از دست‌رفتن حس، خونریزی کنترل‌نشده، بدشکلی واضح یا ناتوانی حرکت به ارزیابی فوری نیاز دارد.", ar: "الألم الشديد أو تغير اللون أو فقد الإحساس أو النزف غير المضبوط أو التشوه الكبير أو فقد الحركة يحتاج إلى تقييم عاجل." },
  },
  {
    slug: "when-to-seek-emergency-care", category: "care",
    title: { en: "When to seek emergency care", fa: "چه زمانی باید به اورژانس مراجعه کرد؟", ar: "متى نطلب رعاية الطوارئ؟" },
    excerpt: { en: "Crush injury, amputation, open fracture, threatened circulation, severe contamination, or rapidly increasing swelling should not wait for routine booking.", fa: "له‌شدگی، قطع عضو، شکستگی باز، اختلال خون‌رسانی، آلودگی شدید یا تورم سریع نباید تا نوبت معمول منتظر بماند.", ar: "السحق أو البتر أو الكسر المفتوح أو ضعف التروية أو التلوث الشديد أو التورم السريع لا ينبغي أن ينتظر موعداً روتينياً." },
  },
  {
    slug: "hand-reconstruction-and-myoelectric-prostheses", category: "innovation", tags: ["news", "innovation"], image: "/media/news/best-paper-meeting.jpg",
    title: { en: "Hand reconstruction and myoelectric prostheses", fa: "بازسازی دست و پروتزهای مایوالکتریک", ar: "إعادة بناء اليد والأطراف العضلية الكهربائية" },
    excerpt: { en: "Reconstruction and prosthetic pathways answer different clinical needs; anatomy, function, goals, rehabilitation, and device tolerance shape the decision.", fa: "بازسازی و پروتز به نیازهای بالینی متفاوت پاسخ می‌دهند؛ آناتومی، عملکرد، هدف بیمار، توان‌بخشی و تحمل وسیله در تصمیم نقش دارند.", ar: "تلبي إعادة البناء والأطراف التعويضية احتياجات مختلفة، ويؤثر التشريح والوظيفة والأهداف والتأهيل وتحمل الجهاز في القرار." },
  },
  {
    slug: "hydroxyapatite-pin-coating-research", category: "research", tags: ["news", "research"], image: "/media/news/top-cited.jpg",
    title: { en: "Research impact in external-fixator pin coatings", fa: "اثرگذاری پژوهش پوشش پین‌های فیکساتور", ar: "أثر بحث طلاء مسامير المثبت الخارجي" },
    excerpt: { en: "Research on hydroxyapatite-based external-fixator pin coatings was recognized for citation impact; attribution should follow the underlying publication record.", fa: "پژوهش پوشش هیدروکسی‌آپاتیت پین‌های فیکساتور خارجی از نظر اثر استنادی مورد توجه قرار گرفت؛ انتساب باید مطابق سابقه انتشار مقاله باشد.", ar: "حظي بحث طلاء مسامير المثبت الخارجي بالهيدروكسي أباتيت بتقدير لأثر الاستشهاد، ويجب أن يتبع الإسناد سجل النشر الأصلي." },
  },
  {
    slug: "orthopedic-congress-presentation-recognition", category: "research", tags: ["news", "research"], image: "/media/news/congress-recognition.jpg",
    title: { en: "Orthopedic congress presentation recognition", fa: "تقدیر از ارائه در کنگره ارتوپدی", ar: "تقدير عرض في مؤتمر جراحة العظام" },
    excerpt: { en: "A selected scientific presentation connected clinical and engineering work in upper-limb innovation; the final public wording remains tied to the verified certificate record.", fa: "یک ارائه علمی منتخب، فعالیت بالینی و مهندسی در نوآوری اندام فوقانی را به هم پیوند داد؛ متن نهایی عمومی باید مطابق مدرک تأییدشده باشد.", ar: "ربط عرض علمي مختار بين العمل السريري والهندسي في ابتكار الطرف العلوي، وتبقى الصياغة النهائية مرتبطة بالسجل الموثق." },
  },
];

export const contentPosts: ContentPost[] = entries.map((entry, index) => ({
  ...entry,
  category: categories[entry.category],
  tags: Array.from(new Set<ContentTag>(["blog", entry.category as ContentTag, ...(entry.tags ?? [])])),
  image: entry.image ?? images[index % images.length],
  date: `2026-${String((index % 6) + 1).padStart(2, "0")}-${String((index % 20) + 4).padStart(2, "0")}`,
  readMinutes: 4 + (index % 4),
}));

export const blogPosts = contentPosts;

export function postsForTag(tag: ContentTag) {
  return contentPosts.filter((post) => post.tags.includes(tag));
}

export const blogLabels: Record<Locale, {
  kicker: string; title: string; intro: string; read: string; minutes: string;
  back: string; overview: string; assessment: string; nextSteps: string;
  assessmentText: string; nextText: string; disclaimer: string;
}> = {
  en: {
    kicker: "Blog", title: "Practical education for hand and upper-extremity health.", intro: "A growing library for patients, learners, and collaborators. Content is educational and never replaces individual assessment.", read: "Read article", minutes: "min read", back: "Back to all articles", overview: "Overview", assessment: "Why assessment matters", nextSteps: "Treatment and next steps", assessmentText: "Similar symptoms may arise from different tissues or levels of injury. History, examination, and appropriate imaging or tests help define the diagnosis, severity, and safest care pathway.", nextText: "Options may include observation, activity adjustment, therapy, medication, splinting, an office procedure, or surgery. The right sequence depends on the individual case and should be agreed with the treating team.", disclaimer: "This article is general education. It does not provide a diagnosis or emergency response." },
  fa: {
    kicker: "وبلاگ", title: "آموزش کاربردی سلامت دست و اندام فوقانی.", intro: "کتابخانه‌ای رو به رشد برای بیماران، فراگیران و همکاران؛ این محتوا آموزشی است و جایگزین ارزیابی فردی نیست.", read: "مطالعه مقاله", minutes: "دقیقه مطالعه", back: "بازگشت به همه مقاله‌ها", overview: "مرور موضوع", assessment: "چرا ارزیابی اهمیت دارد؟", nextSteps: "درمان و گام بعدی", assessmentText: "علائم مشابه ممکن است از بافت‌ها یا سطوح متفاوت آسیب ناشی شوند. شرح حال، معاینه و تصویر یا آزمایش مناسب به تشخیص، تعیین شدت و انتخاب ایمن‌ترین مسیر کمک می‌کند.", nextText: "گزینه‌ها می‌توانند شامل پایش، اصلاح فعالیت، توان‌بخشی، دارو، آتل، اقدام مطب یا جراحی باشند. ترتیب درست به شرایط فردی وابسته است و باید با تیم درمان تعیین شود.", disclaimer: "این مقاله آموزش عمومی است و تشخیص پزشکی یا پاسخ اورژانسی ارائه نمی‌کند." },
  ar: {
    kicker: "المدونة", title: "تثقيف عملي لصحة اليد والطرف العلوي.", intro: "مكتبة متنامية للمرضى والمتعلمين والمتعاونين؛ المحتوى تعليمي ولا يستبدل التقييم الفردي.", read: "اقرأ المقال", minutes: "دقائق قراءة", back: "العودة إلى كل المقالات", overview: "نظرة عامة", assessment: "لماذا يهم التقييم؟", nextSteps: "العلاج والخطوات التالية", assessmentText: "قد تنشأ أعراض متشابهة من أنسجة أو مستويات إصابة مختلفة. يساعد التاريخ والفحص والصور أو الاختبارات المناسبة في تحديد التشخيص والشدة والمسار الأكثر أماناً.", nextText: "قد تشمل الخيارات المراقبة وتعديل النشاط والتأهيل والدواء والجبيرة وإجراء في العيادة أو الجراحة. يعتمد التسلسل الصحيح على الحالة الفردية ويُتفق عليه مع الفريق المعالج.", disclaimer: "هذا المقال للتثقيف العام ولا يقدم تشخيصاً أو استجابة للطوارئ." },
};

export function findBlogPost(slug: string) {
  return contentPosts.find((post) => post.slug === slug);
}
