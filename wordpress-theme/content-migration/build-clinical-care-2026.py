"""Build reviewed, editable EN/FA/AR clinical pages from the owner's supplied copy."""
import json, re, sys
from html import escape as e
from pathlib import Path
ROOT=Path(__file__).resolve().parents[2]
WORK=ROOT.parent
BOOK='https://nobat.ir/9705'
BASE='https://dralimoradi.com'
COPY={
'fa':{
 'clinicTitle':'اقدامات قبل و بعد از عمل کلینیک','hospitalTitle':'اقدامات قبل و بعد از عمل بیمارستان',
 'clinicService':'خدمات کلینیک','hospitalService':'خدمات بیمارستان',
 'before':'پیش از عمل','after':'پس از عمل','guide':'راهنمای آمادگی و مراقبت','services':'خدمات تخصصی دست و اندام فوقانی',
 'clinicIntro':'راهنمای اقدامات سرپایی و مراقبت پس از جراحی در مطب دکتر علی مرادی.',
 'hospitalIntro':'قابل توجه بیماران کاندید عمل جراحی دست و اندام فوقانی بستری در سرویس دکتر علی مرادی؛ خواهشمند است قبل و بعد از عمل به نکات زیر توجه فرمایید.',
 'clinicBefore':[
  'دو عدد آنتی‌بیوتیک سفالکسین، دو ساعت قبل از عمل مصرف شود.',
  'یک عدد مسکن (ژلوفن، استامینوفن و…) یا یک عدد شیاف، نیم ساعت قبل از عمل مصرف شود.',
  'عمل شما نیاز به ناشتایی ندارد و تا نیم ساعت قبل از عمل می‌توانید نوشیدنی یا غذای سبک میل کنید.'],
 'clinicAfter':[
  'کپسول آنتی‌بیوتیک سفالکسین، هر ۶ ساعت یک عدد مصرف شود. تعداد سه عدد کافی است و پس از آن نیازی به مصرف آنتی‌بیوتیک ندارید.',
  'مسکن یا شیاف را در صورت داشتن درد، هر ۳ تا ۶ ساعت می‌توانید مصرف کنید.',
  'پانسمان دست را به مدت یک هفته به هیچ عنوان باز نکنید. برای هفتهٔ آینده، در همان روز انجام عمل نوبت بگیرید و برای تعویض پانسمان به مطب مراجعه کنید.',
  'برای استحمام می‌توانید از پلاستیک یا سلفون روی دست استفاده کنید. پانسمان نباید با آب تماس داشته باشد یا خیس شود.',
  'از انجام کارهای سنگین به مدت ۳ تا ۴ ماه خودداری کنید.',
  'بعد از عمل محدودیت غذایی ندارید.',
  'اگر درد پس از مصرف مسکن بهبود نیافت، تورم شدید یا خواب‌رفتگی ممتد وجود داشت، یا به هر دلیل پانسمان خیس شد، به مطب یا اورژانس بیمارستان مراجعه کنید.',
  'اندام به مدت ۴۸ ساعت آویز گردن باشد.'],
 'hospitalBefore':[
  'اگر به وسیلهٔ عمل نیاز دارید، باید پیش از عمل تهیه شود. هنگام بستری با بخش مربوطه در بیمارستان برای استریل کردن وسیله هماهنگ کنید.',
  'هشت ساعت قبل از عمل جراحی، ناشتایی ضرورت دارد. در روز عمل از ساعت ۶ صبح به بعد، خوردن هرگونه مواد آشامیدنی و خوراکی ممنوع است.',
  'در صورت درخواست مشاوره توسط پزشک، مشاوره باید قبل از بستری انجام شود.',
  'پیش از بستری دوش بگیرید، ناخن‌ها را کوتاه کنید و دست فاقد لاک، زیورآلات یا هر وسیلهٔ فلزی باشد. بهتر است برای استحمام از شامپوی آنتی‌باکتریال (کلرهگزیدین ۲٪) یا شامپو بچه استفاده شود. برای از بین بردن موهای ناحیهٔ جراحی حتماً از ماشین اصلاح (موزر) استفاده کنید.'],
 'documentsTitle':'مدارک و هماهنگی پذیرش',
 'documents':['برگهٔ بستری','برگهٔ مشورت در صورت درخواست پزشک','تهیهٔ وسیلهٔ عمل و هماهنگی با بخش برای استریل کردن آن','مدارک پزشکی شامل عکس رادیوگرافی، سی‌تی‌اسکن، ام‌آر‌آی و نوار عصب، یا خلاصهٔ پروندهٔ عمل‌های قبلی'],
 'consultations':'مشورت بیهوشی · مشورت قلب · وسیلهٔ عمل',
 'cancel':'در صورت لغو عمل جراحی، حتماً دو روز قبل از طریق پیامک به شمارهٔ 09054501141 اطلاع دهید.',
 'hospitalAfter':[
  'بعد از عمل جراحی، به هیچ عنوان پانسمان را باز یا خیس نکنید.',
  'تاریخ مراجعهٔ بعدی یک هفته پس از عمل است، مگر اینکه در ویزیت حضوری روز بعد از عمل، تاریخ مشخص دیگری تعیین شده باشد.',
  'دست باید آویز گردن باشد. فقط اگر اجازهٔ حرکت انگشتان به شما داده شده است، می‌توانید انگشتان را باز و بسته کنید.',
  'نسخهٔ داروهای مورد نیاز را پس از ترخیص از ایستگاه پرستاری بخش دریافت کنید. اگر در نسخه آنتی‌بیوتیک تجویز نشده باشد، نیازی به مصرف خودسرانهٔ آن نیست.',
  'در صورت بروز هرگونه مشکل یا درد خارج از تحمل، از طریق تلگرام یا پیامک با شمارهٔ 09054501141 در ارتباط باشید.'],
 'clinicOverview':'این مرکز با رویکردی نوآورانه و بیمارمحور، بستری اختصاصی برای ارائهٔ خدمات کلینیکی و جراحی‌های مینور در محیطی کاملاً خصوصی و بدون حضور دستیار فراهم آورده است. دکتر مرادی با بهره‌گیری از تکنیک‌های کم‌تهاجمی ابداعی و پروتکل‌های درمانی هوشمند، مسیری کوتاه‌تر، ایمن‌تر و با حداقل عارضه برای بیماران طراحی کرده‌اند. تمامی خدمات با بالاترین سطح حریم خصوصی، وقت‌گذاری شخصی و دقت میکروسکوپی انجام می‌شود.',
 'minorTitle':'جراحی‌های مینور تخصصی دست، به‌صورت سرپایی',
 'minor':['رهایی عصب در سندرم تونل کارپال با روش‌های نوین و حداقل برش','جراحی انگشت ماشه‌ای (تریگر فینگر) و رفع گره‌های تاندونی','درمان تنوسینوویت دکوروان و درگیری تاندون‌های مچ دست','درمان تنیس‌البو و گلف‌باز‌البو؛ اپی‌کوندیلیت جانبی و داخلی','جااندازی و تثبیت شکستگی‌های ساده و مینور استخوان‌های متاکارپ و فالانژ','ترمیم آسیب عصب‌های حسی کوچک و تاندون‌های خم‌کننده و بازکنندهٔ سطحی در اورژانس‌های خفیف تا متوسط','جااندازی شکستگی‌های مناسب با بی‌حسی در مطب و گچ‌گیری'],
 'outpatientTitle':'خدمات کلینیکی و درمانی تخصصی سرپایی',
 'outpatient':['تزریق تخصصی داخل مفصل و اطراف تاندون، شامل کورتیکواستروئید، هیالورونیک اسید و سایر داروهای بیولوژیک','درمان‌های پیشرفتهٔ بازساختی با سلول‌های بنیادی برای آرتروز مفاصل، تاندونوپاتی و آسیب لیگامان‌های اندام فوقانی، با هدف بازسازی بافت و بازیابی عملکرد طبیعی','پانسمان‌های پیشرفته و بیولوژیک برای زخم جراحی و آسیب بافت نرم','گچ‌گیری و آتل‌گیری تخصصی بر اساس جدیدترین پروتکل‌های ارتوپدی برای بی‌حرکت‌سازی اصولی اندام فوقانی','جااندازی بستهٔ دررفتگی و شکستگی ساده، در صورت نیاز تحت هدایت فلوروسکوپی','آموزش و تجویز تمرینات اولیهٔ توان‌بخشی برای تسریع بهبودی'],
 'razaviTitle':'بیمارستان رضوی؛ خدمات خصوصی و جراحی‌های ماژور و فوق‌تخصصی',
 'razaviOverview':'بیمارستان رضوی به عنوان پیشرفته‌ترین و به‌روزترین بیمارستان شرق کشور شناخته می‌شود و خدمات باکیفیت با پذیرش گستردهٔ بیمه‌های مکمل ارائه می‌کند. این مرکز با رویکردی کاملاً خصوصی و بدون حضور دستیار آموزشی، بستر انجام جراحی‌های ماژور و فوق‌تخصصی دست و اندام فوقانی با بالاترین سطح امکانات بیمارستانی و تیم پرستاری اختصاصی را فراهم کرده است. تمامی اقدامات شخصاً توسط دکتر مرادی و با بهره‌گیری از پیشرفته‌ترین فناوری‌های روز انجام می‌شود. بیماران در محیطی خصوصی و آرام، با بالاترین استانداردهای مراقبت و کنترل عفونت، خدمات دریافت می‌کنند.',
 'majorTitle':'جراحی‌های ماژور، فوق‌تخصصی و بازسازی اندام فوقانی',
 'major':['پیوند و ترمیم تاندون و عصب ناشی از جراحت (Tendon Repair & Nerve Grafting)','درمان آسیب‌های ورزشی دست و اندام فوقانی','انتقال تاندون برای بازیابی عملکردهای از دست‌رفته','پیوند انگشت و جراحی پیچیدهٔ بازسازی اندام فوقانی، دست و انگشتان','درمان و تثبیت شکستگی پیچیده و نقص استخوانی؛ جراحی پیچیدهٔ مشکلات مادرزادی و اندام فوقانی کودکان','جراحی تومور و توده‌های بافت نرم و استخوانی اندام فوقانی','میکروسرجری اندام فوقانی و تحتانی برای ترمیم عروق و اعصاب ظریف','آرتروسکوپی مچ دست، آرنج و شانه برای تشخیص و درمان ضایعات مفصلی'],
 'coordination':'برای دریافت نوبت جراحی در بیمارستان رضوی، ابتدا در مطب خصوصی ویزیت شوید؛ سپس دفتر دکتر مرادی هماهنگی‌های لازم برای بستری و جراحی را انجام می‌دهد.',
 'imamTitle':'بیمارستان امام رضا (ع)؛ مرکز دولتی و آموزشی',
 'imamOverview':'خدمات این مرکز به‌صورت دولتی و آموزشی ارائه می‌شود. تمامی اقدامات تحت نظارت مستقیم استاد مرادی و با همکاری دستیاران تخصصی (رزیدنت‌ها) انجام می‌شود. این مرکز بستر آموزش پزشکان متخصص آینده و ارائهٔ خدمات به طیف وسیعی از بیماران است. انواع جراحی‌های ماژور و فوق‌تخصصی دست و اندام فوقانی در این بیمارستان انجام می‌شود.',
 'bookingNote':'فرآیند نوبت‌گیری مراکز دولتی و خصوصی کاملاً مجزا و مستقل است. برای هر مرکز از مسیر نوبت‌دهی مربوط استفاده کنید. پس از دریافت نوبت اینترنتی، در زمان تعیین‌شده به آدرس همان مرکز مراجعه کنید. پذیرش حضوری بدون نوبت اینترنتی در هیچ‌یک از مراکز امکان‌پذیر نیست.',
 'hubTitle':'دروازهٔ ورود به درمان‌های نوآورانه و تخصصی بیماری‌های دست و اندام فوقانی',
 'hubIntro':'فعالیت‌های درمانی دکتر علی مرادی در دو حوزهٔ خصوصی و دولتی، در بالاترین سطح دانش علمی و با به‌روزترین تکنیک‌های جراحی انجام می‌شود. فعالیت خصوصی ایشان شامل مطب و بیمارستان رضوی و فعالیت دولتی شامل بیمارستان امام رضا (ع)، در جایگاه استاد دانشگاه است.',
 'hubPrivate':'مرکز خصوصی دکتر مرادی به‌عنوان قطب تخصصی جراحی دست و اندام فوقانی، مستقل از سیستم درمانی دولتی فعالیت می‌کند. رسالت ما ارائهٔ مراقبت‌های متمایز، وقت‌شناسانه و پیشرفته است. این مرکز محل اصلی پذیرش، ویزیت، درمان و پیگیری بیمارانی است که خواهان خدمات تخصصی در محیطی خصوصی و با بالاترین سطح امکانات هستند.',
 'benefitTitle':'مزیت کلیدی مرکز خصوصی دکتر مرادی',
 'benefit':'دکتر مرادی به‌عنوان جراح فوق‌تخصص دست، مخترع و کارآفرین در حوزهٔ دست و اندام فوقانی، بر کوتاه‌ترین، ایمن‌ترین و مؤثرترین مسیر درمان با کمترین عارضه تمرکز دارد. ترکیب دانش آکادمیک، مهارت‌های بالینی پیشرفته و نگاه کارآفرینانه، زمینهٔ طراحی و اجرای راهکارهای نوین درمانی را فراهم کرده است. مطب خصوصی بستر ایده‌آل اجرای این نوآوری‌های بیمارمحور و پیشگامانه است. هر بیمار یک «شریک درمانی» ارزشمند است که بالاترین سطح دقت، ایمنی و مراقبت محبت‌آمیز را دریافت می‌کند.',
 'hubCta':'برای رزرو نوبت در مطب و تجربهٔ سفری درمانی هوشمندانه، یکپارچه و متمایز، از لینک نوبت‌دهی آنلاین اقدام کنید. برای مرکز دولتی نیز از مسیر نوبت‌دهی بیمارستان امام رضا استفاده کنید؛ هماهنگی جراحی در بیمارستان رضوی پس از ویزیت مطب انجام می‌شود.',
 'choose':'برای اطلاعات بیشتر دربارهٔ درمان، یکی از دو مسیر زیر را انتخاب کنید.',
 'book':'دریافت نوبت آنلاین','clinicAddress':'مشهد، گلستان شرقی ۶، روبه‌روی پارکینگ بیمارستان آریا، پلاک ۱۷، ساختمان پورسینا، طبقهٔ سوم',
 'razaviAddress':'مشهد، بزرگراه پیامبر اعظم، بعد از پل قائم، بیمارستان رضوی','imamAddress':'مشهد، میدان امام رضا، بیمارستان امام رضا (ع)',
 'address':'نشانی و مراجعه','readGuide':'راهنمای کامل قبل و بعد از عمل','prepare':'لطفاً پیش از عمل به راهنمای زیر توجه کنید.'},
'en':{
 'clinicTitle':'Before and after clinic surgery','hospitalTitle':'Before and after hospital surgery',
 'clinicService':'Clinic services','hospitalService':'Hospital services','before':'Before surgery','after':'After surgery','guide':'Preparation and recovery guide','services':'Specialist hand and upper-extremity services',
 'clinicIntro':'Preparation and recovery guidance for outpatient procedures at Dr. Ali Moradi’s private office.',
 'hospitalIntro':'For patients scheduled for hand and upper-extremity surgery under Dr. Ali Moradi’s hospital service: please follow the preparation and postoperative instructions below.',
 'clinicBefore':['Take two cephalexin antibiotic capsules two hours before the procedure.','Take one pain-relief tablet (Gelofen, acetaminophen, etc.) or one suppository half an hour before the procedure.','Your procedure does not require fasting. You may have a drink or a light meal until half an hour before the procedure.'],
 'clinicAfter':['Take one cephalexin antibiotic capsule every 6 hours. Three capsules in total are sufficient; no further antibiotics are needed.','If you have pain, you may take a pain-relief tablet or suppository every 3–6 hours.','Do not remove the hand dressing for one week. Book an appointment for the same weekday in the following week and attend the office for a dressing change.','For bathing, cover the hand with a plastic bag or cling film. The dressing must not come into contact with water or become wet.','Avoid heavy work for 3–4 months.','There are no dietary restrictions after the procedure.','If pain does not improve after pain relief, if there is severe swelling or persistent numbness, or if the dressing becomes wet for any reason, attend the office or the hospital emergency department.','Keep the limb in a neck sling for 48 hours.'],
 'hospitalBefore':['If a surgical device is required, obtain it before surgery. At admission, coordinate sterilization with the relevant hospital ward.','Fasting is required for eight hours before surgery. On the day of surgery, do not eat or drink anything from 6 a.m. onward.','Complete any consultation requested by the doctor before hospital admission.','Shower before admission, trim your nails, and remove nail polish, jewellery and all metal items from the hand. Antibacterial shampoo (2% chlorhexidine) or baby shampoo is recommended for bathing. Use an electric clipper, rather than another method, to remove hair from the surgical area.'],
 'documentsTitle':'Admission documents and coordination','documents':['Admission form','Consultation report, if requested by the doctor','Required surgical device and coordination with the ward for sterilization','Medical records: radiographs, CT, MRI, nerve studies or summaries of previous operations'],
 'consultations':'Anaesthesia consultation · Cardiology consultation · Surgical device',
 'cancel':'If you cancel surgery, notify the team by SMS at 09054501141 at least two days in advance.',
 'hospitalAfter':['Do not remove or wet the dressing after surgery.','Your next visit is one week after surgery, unless a different date is set during the in-person review on the following day.','Keep the hand in a neck sling. Open and close your fingers only if you have been given permission to move them.','Collect the prescription for your required medicines from the ward nursing station after discharge. If no antibiotic is prescribed, do not take one on your own.','For any problem or intolerable pain, contact 09054501141 by Telegram or SMS.'],
 'clinicOverview':'With an innovative, patient-centred approach, this centre provides dedicated clinical care and minor surgery in a fully private setting without trainees present. Dr. Moradi uses his own minimally invasive techniques and intelligent treatment protocols to design shorter, safer pathways with minimal complications. Services are delivered with the highest level of privacy, personal attention and microscopic precision.',
 'minorTitle':'Specialist outpatient minor hand surgery','minor':['Carpal tunnel nerve release using modern techniques with minimal incisions','Trigger finger surgery and treatment of tendon nodules','Treatment of de Quervain tenosynovitis involving the wrist tendons','Treatment of tennis elbow and golfer’s elbow: lateral and medial epicondylitis','Reduction and fixation of simple minor metacarpal and phalangeal fractures','Repair of small sensory nerves and superficial flexor and extensor tendons in mild-to-moderate urgent cases','Reduction of suitable fractures under local anaesthesia in the office, followed by casting'],
 'outpatientTitle':'Specialist outpatient clinical treatments','outpatient':['Specialist intra-articular and peritendinous injections, including corticosteroids, hyaluronic acid and other biological medicines','Advanced regenerative treatments using stem cells for upper-extremity osteoarthritis, tendinopathies and ligament injuries, aiming to restore tissue and natural function','Advanced and biological dressings for surgical wounds and soft-tissue injuries','Specialist casting and splinting following current orthopaedic protocols for appropriate upper-extremity immobilization','Closed reduction of dislocations and simple fractures, with fluoroscopic guidance when required','Instruction and prescription of early rehabilitation exercises to support faster recovery'],
 'razaviTitle':'Razavi Hospital: private major and subspecialty surgery',
 'razaviOverview':'Razavi Hospital is recognized as the most advanced and up-to-date hospital in eastern Iran, providing high-quality care with broad acceptance of supplementary insurance. Its fully private setting, without educational trainees, supports major and subspecialty hand and upper-extremity operations with extensive hospital facilities and a dedicated nursing team. All procedures are performed personally by Dr. Moradi using advanced contemporary technology. Patients receive care in a private, calm environment with the highest standards of care and infection control.',
 'majorTitle':'Major, subspecialty and reconstructive surgery','major':['Tendon repair and nerve grafting following injury','Treatment of sports injuries of the hand and upper extremity','Tendon transfer to restore lost function','Finger replantation and complex reconstruction of the upper extremity, hand and digits','Treatment and fixation of complex fractures and bone defects; complex congenital and paediatric upper-extremity surgery','Surgery for upper-extremity soft-tissue and bone tumours and masses','Upper- and lower-extremity microsurgery to repair fine blood vessels and nerves','Wrist, elbow and shoulder arthroscopy for diagnosis and treatment of joint lesions'],
 'coordination':'To arrange surgery at Razavi Hospital, first attend a consultation at the private office. Dr. Moradi’s office will then coordinate admission and surgery.',
 'imamTitle':'Imam Reza Hospital: public and teaching centre',
 'imamOverview':'Care at this centre is provided within the public and teaching system. All treatment is carried out under Professor Moradi’s direct supervision and in collaboration with specialist trainees (residents). The hospital trains future specialists and serves a broad range of patients. Its surgical scope includes major and subspecialty hand and upper-extremity operations.',
 'bookingNote':'Public and private appointment processes are separate and independent. Use the appointment pathway for the relevant centre. After booking online, attend that centre at the specified time and address. Walk-in admission without an online appointment is not available at any of the centres.',
 'hubTitle':'Your gateway to innovative specialist treatment for hand and upper-extremity conditions',
 'hubIntro':'Dr. Ali Moradi provides care in the private and public sectors at the highest level of scientific knowledge, using current surgical techniques. His private practice includes the office and Razavi Hospital; his public practice is at Imam Reza Hospital in his role as a university professor.',
 'hubPrivate':'Dr. Moradi’s private centre is a specialist hub for hand and upper-extremity surgery, operating independently of the public healthcare system. Its mission is distinctive, punctual and advanced care. It is the principal location for assessment, consultation, treatment and follow-up for patients seeking specialist services in a private setting with the highest level of facilities.',
 'benefitTitle':'The distinctive advantage of Dr. Moradi’s private centre',
 'benefit':'As a hand subspecialist surgeon, inventor and entrepreneur in hand and upper-extremity surgery, Dr. Moradi focuses on the shortest, safest and most effective treatment pathways with the fewest complications. His academic knowledge, advanced clinical skills and entrepreneurial perspective support the continual development and implementation of new treatments. The private office provides an ideal setting for these pioneering, patient-centred innovations. Every patient is a valued treatment partner, receiving the highest level of precision, safety and compassionate care.',
 'hubCta':'Book online to visit the private office and experience an intelligent, integrated and distinctive treatment journey. For public-sector care, use the Imam Reza Hospital appointment pathway. Surgery at Razavi Hospital is coordinated after an office consultation.',
 'choose':'For further treatment information, choose one of the two care pathways below.','book':'Book an appointment online',
 'clinicAddress':'East Golestan 6, opposite Arya Hospital parking, No. 17, Poursina Building, 3rd floor, Mashhad',
 'razaviAddress':'Payambar-e Azam Highway, after Qaem Bridge, Razavi Hospital, Mashhad','imamAddress':'Imam Reza Square, Imam Reza Hospital, Mashhad','address':'Location and appointments','readGuide':'Full preparation and recovery guide','prepare':'Please read the following guidance before surgery.'},
'ar':{
 'clinicTitle':'إجراءات قبل جراحة العيادة وبعدها','hospitalTitle':'إجراءات قبل جراحة المستشفى وبعدها',
 'clinicService':'خدمات العيادة','hospitalService':'خدمات المستشفى','before':'قبل الجراحة','after':'بعد الجراحة','guide':'دليل الاستعداد والتعافي','services':'خدمات متخصصة لليد والطرف العلوي',
 'clinicIntro':'إرشادات الاستعداد والتعافي للإجراءات الخارجية في عيادة الدكتور علي مرادي الخاصة.',
 'hospitalIntro':'إلى المرضى المرشحين لجراحة اليد والطرف العلوي ضمن خدمة الدكتور علي مرادي في المستشفى: يرجى مراعاة الإرشادات التالية قبل الجراحة وبعدها.',
 'clinicBefore':['تناول كبسولتين من المضاد الحيوي سيفالكسين قبل العملية بساعتين.','تناول قرصاً واحداً من مسكن الألم (جيلوفين أو باراسيتامول وغيرها) أو تحميلة واحدة قبل العملية بنصف ساعة.','لا تحتاج عمليتك إلى الصيام. يمكنك تناول مشروب أو وجبة خفيفة حتى نصف ساعة قبل العملية.'],
 'clinicAfter':['تناول كبسولة واحدة من سيفالكسين كل ٦ ساعات. تكفي ثلاث كبسولات إجمالاً، ولا حاجة إلى مزيد من المضاد الحيوي بعدها.','عند وجود الألم، يمكنك تناول مسكن أو استخدام تحميلة كل ٣ إلى ٦ ساعات.','لا تفتح ضماد اليد مطلقاً لمدة أسبوع. احجز موعداً للأسبوع التالي في اليوم نفسه من الأسبوع، وراجع العيادة لتغيير الضماد.','عند الاستحمام، غطِّ اليد بكيس بلاستيكي أو غلاف بلاستيكي. يجب ألا يلامس الضماد الماء أو يبتل.','تجنب الأعمال الثقيلة لمدة ٣ إلى ٤ أشهر.','لا توجد قيود غذائية بعد العملية.','إذا لم يتحسن الألم بعد المسكن، أو وُجد تورم شديد أو خدر مستمر، أو ابتل الضماد لأي سبب، فراجع العيادة أو قسم طوارئ المستشفى.','ضع الطرف في حمالة حول الرقبة لمدة ٤٨ ساعة.'],
 'hospitalBefore':['إذا كانت العملية تتطلب جهازاً جراحياً، فيجب تأمينه قبل الجراحة. عند الدخول، نسّق مع القسم المعني في المستشفى لتعقيم الجهاز.','يلزم الصيام لمدة ثماني ساعات قبل الجراحة. في يوم العملية، يُمنع تناول أي طعام أو شراب من الساعة السادسة صباحاً فصاعداً.','إذا طلب الطبيب استشارة، فيجب إتمامها قبل الدخول إلى المستشفى.','استحم قبل الدخول، وقص الأظافر، وأزل طلاء الأظافر والحلي وأي جسم معدني من اليد. يُفضّل استخدام شامبو مضاد للبكتيريا (كلورهكسيدين ٢٪) أو شامبو الأطفال. لإزالة شعر منطقة الجراحة، استخدم ماكينة حلاقة كهربائية حصراً.'],
 'documentsTitle':'مستندات الدخول والتنسيق','documents':['ورقة الدخول إلى المستشفى','تقرير الاستشارة إذا طلبه الطبيب','تأمين الجهاز الجراحي والتنسيق مع القسم لتعقيمه','السجلات الطبية: صور الأشعة السينية والتصوير المقطعي والرنين المغناطيسي وفحص الأعصاب، أو ملخصات العمليات السابقة'],
 'consultations':'استشارة التخدير · استشارة القلب · الجهاز الجراحي',
 'cancel':'عند إلغاء العملية، أبلغ الفريق برسالة نصية إلى 09054501141 قبل الموعد بيومين على الأقل.',
 'hospitalAfter':['لا تفتح الضماد أو تبلله بأي حال بعد الجراحة.','المراجعة التالية بعد أسبوع من العملية، إلا إذا حُدد موعد مختلف خلال المراجعة الحضورية في اليوم التالي للجراحة.','يجب وضع اليد في حمالة حول الرقبة. لا تفتح الأصابع وتغلقها إلا إذا سُمح لك بتحريكها.','استلم وصفة الأدوية المطلوبة من محطة تمريض القسم بعد الخروج. إذا لم يُوصف مضاد حيوي، فلا تتناوله من تلقاء نفسك.','عند حدوث أي مشكلة أو ألم لا يُحتمل، تواصل عبر تلغرام أو الرسائل النصية مع الرقم 09054501141.'],
 'clinicOverview':'يقدم هذا المركز، بمنهج مبتكر يركز على المريض، خدمات سريرية وجراحات صغرى في بيئة خاصة بالكامل دون حضور المتدربين. يستخدم الدكتور مرادي تقنيات قليلة التدخل ابتكرها وبروتوكولات علاج ذكية لتصميم مسارات أقصر وأكثر أماناً وبأقل مضاعفات. تُقدم جميع الخدمات بأعلى مستوى من الخصوصية والوقت الشخصي والدقة المجهرية.',
 'minorTitle':'جراحات اليد الصغرى المتخصصة للمرضى الخارجيين','minor':['تحرير العصب في متلازمة النفق الرسغي بتقنيات حديثة وشقوق محدودة','جراحة الإصبع الزنادي وعلاج عقد الأوتار','علاج التهاب غمد أوتار دي كيرفان في الرسغ','علاج مرفق التنس ومرفق لاعب الغولف؛ التهاب اللقيمة الوحشية والإنسية','رد وتثبيت الكسور البسيطة والصغرى لعظام المشط والسلاميات','ترميم الأعصاب الحسية الصغيرة وأوتار الثني والبسط السطحية في الحالات العاجلة الخفيفة إلى المتوسطة','رد الكسور المناسبة بالتخدير الموضعي في العيادة ووضع الجبس'],
 'outpatientTitle':'خدمات سريرية وعلاجية متخصصة للمرضى الخارجيين','outpatient':['حقن متخصصة داخل المفاصل وحول الأوتار، تشمل الكورتيكوستيرويدات وحمض الهيالورونيك وأدوية بيولوجية أخرى','علاجات تجديدية متقدمة بالخلايا الجذعية لخشونة مفاصل الطرف العلوي واعتلالات الأوتار وإصابات الأربطة، بهدف إعادة بناء النسيج واستعادة الوظيفة الطبيعية','ضمادات متقدمة وبيولوجية للجروح الجراحية وإصابات الأنسجة الرخوة','جبس وجبائر متخصصة وفق أحدث بروتوكولات جراحة العظام لتثبيت الطرف العلوي بصورة سليمة','رد مغلق للخلوع والكسور البسيطة، مع التوجيه بالتنظير التألقي عند الحاجة','تعليم ووصف تمارين التأهيل الأولية لتسريع التعافي'],
 'razaviTitle':'مستشفى رضوي؛ جراحات كبرى وفائقة التخصص في القطاع الخاص',
 'razaviOverview':'يُعرف مستشفى رضوي بأنه الأكثر تقدماً وحداثة في شرق إيران، ويقدم خدمات عالية الجودة مع قبول واسع للتأمينات التكميلية. يوفر بيئة خاصة بالكامل دون حضور متدربين تعليميين لإجراء جراحات اليد والطرف العلوي الكبرى وفائقة التخصص، مع تجهيزات مستشفى متقدمة وفريق تمريض مخصص. يجري الدكتور مرادي جميع الإجراءات شخصياً باستخدام أحدث التقنيات. يحصل المرضى على الرعاية في بيئة خاصة وهادئة وفق أعلى معايير الرعاية ومكافحة العدوى.',
 'majorTitle':'جراحات كبرى وفائقة التخصص وترميم الطرف العلوي','major':['ترميم الأوتار وترقيع الأعصاب بعد الإصابات','علاج الإصابات الرياضية لليد والطرف العلوي','نقل الأوتار لاستعادة الوظائف المفقودة','إعادة زرع الأصابع والترميم المعقد للطرف العلوي واليد والأصابع','علاج وتثبيت الكسور المعقدة والعيوب العظمية؛ جراحات معقدة للتشوهات الخلقية والطرف العلوي لدى الأطفال','جراحة أورام وكتل الأنسجة الرخوة والعظام في الطرف العلوي','الجراحة المجهرية للطرفين العلوي والسفلي لترميم الأوعية والأعصاب الدقيقة','تنظير مفاصل الرسغ والمرفق والكتف لتشخيص وعلاج الآفات المفصلية'],
 'coordination':'لتنسيق الجراحة في مستشفى رضوي، راجع العيادة الخاصة أولاً. بعد الزيارة، يتولى مكتب الدكتور مرادي تنسيق الدخول والجراحة.',
 'imamTitle':'مستشفى الإمام رضا (ع)؛ مركز حكومي وتعليمي',
 'imamOverview':'تُقدم الرعاية في هذا المركز ضمن النظام الحكومي والتعليمي. تُجرى جميع الإجراءات تحت الإشراف المباشر للأستاذ مرادي وبالتعاون مع الأطباء المقيمين المتخصصين. يتيح المركز تدريب أطباء الاختصاص المستقبليين وخدمة طيف واسع من المرضى. يشمل نطاق الجراحة مختلف عمليات اليد والطرف العلوي الكبرى وفائقة التخصص.',
 'bookingNote':'إجراءات حجز المواعيد في القطاعين الحكومي والخاص منفصلة ومستقلة تماماً. استخدم مسار الحجز الخاص بالمركز المعني. بعد الحجز الإلكتروني، راجع عنوان المركز نفسه في الوقت المحدد. لا يتاح الاستقبال الحضوري دون موعد إلكتروني في أي من المراكز.',
 'hubTitle':'بوابتك إلى العلاجات المبتكرة والمتخصصة لأمراض اليد والطرف العلوي',
 'hubIntro':'يقدم الدكتور علي مرادي الرعاية في القطاعين الخاص والحكومي بأعلى مستوى من المعرفة العلمية وبأحدث التقنيات الجراحية. تشمل ممارسته الخاصة العيادة ومستشفى رضوي، بينما تتم ممارسته الحكومية في مستشفى الإمام رضا (ع) بصفته أستاذاً جامعياً.',
 'hubPrivate':'يعمل مركز الدكتور مرادي الخاص كقطب متخصص في جراحة اليد والطرف العلوي بصورة مستقلة عن النظام الحكومي. تتمثل رسالته في تقديم رعاية متميزة ودقيقة المواعيد ومتقدمة. وهو الموقع الرئيسي لاستقبال وزيارة وعلاج ومتابعة المرضى الراغبين في خدمات متخصصة ضمن بيئة خاصة وبأعلى مستوى من الإمكانات.',
 'benefitTitle':'الميزة الأساسية لمركز الدكتور مرادي الخاص',
 'benefit':'بصفته جراحاً فائق التخصص في اليد ومخترعاً ورائد أعمال في جراحة اليد والطرف العلوي، يركز الدكتور مرادي على أقصر مسارات العلاج وأكثرها أماناً وفعالية بأقل مضاعفات. يدمج المعرفة الأكاديمية والمهارات السريرية المتقدمة والرؤية الريادية لتطوير حلول علاجية جديدة وتطبيقها باستمرار. توفر العيادة الخاصة بيئة مثالية لهذه الابتكارات الرائدة التي تركز على المريض. كل مريض شريك علاجي قيّم يحصل على أعلى مستوى من الدقة والأمان والرعاية الرحيمة.',
 'hubCta':'احجز إلكترونياً لزيارة العيادة الخاصة وتجربة رحلة علاج ذكية ومتكاملة ومتميزة. للرعاية الحكومية استخدم مسار مواعيد مستشفى الإمام رضا. تُنسق الجراحة في مستشفى رضوي بعد زيارة العيادة.',
 'choose':'لمزيد من المعلومات العلاجية، اختر أحد مساري الرعاية أدناه.','book':'حجز موعد إلكتروني',
 'clinicAddress':'مشهد، كلستان الشرقي ٦، مقابل موقف مستشفى آريا، رقم ١٧، مبنى بورسينا، الطابق الثالث',
 'razaviAddress':'مشهد، طريق بيامبر أعظم السريع، بعد جسر قائم، مستشفى رضوي','imamAddress':'مشهد، ساحة الإمام رضا، مستشفى الإمام رضا (ع)','address':'العنوان والمواعيد','readGuide':'دليل الاستعداد والتعافي الكامل','prepare':'يرجى قراءة الإرشادات التالية قبل الجراحة.'}}

def url(slug,lang):return BASE+('/' if lang=='en' else '/'+lang+'/')+slug+'/'
def html(s):return '<!-- wp:html -->\n'+s+'\n<!-- /wp:html -->'
def paras(lines):return ''.join('<p>'+e(p)+'</p>' for p in lines)
def section(title,body,cls=''):
 return '<section class="care-section '+cls+'"><h2>'+e(title)+'</h2>'+body+'</section>'
def guide(area,c):
 before=paras(c[area+'Before']);after=paras(c[area+'After'])
 if area=='hospital':before+=section(c['documentsTitle'],'<ul>'+''.join('<li>'+e(x)+'</li>'for x in c['documents'])+'</ul><p class="care-meta">'+e(c['consultations'])+'</p><p class="care-callout">'+e(c['cancel'])+'</p>')
 return '<div class="care-guide"><div class="care-phase"><span class="care-phase-label">01</span><h2>'+e(c['before'])+'</h2>'+before+'</div><div class="care-phase"><span class="care-phase-label">02</span><h2>'+e(c['after'])+'</h2>'+after+'</div></div>'
def tiles(title,items):
 return section(title,'<div class="care-service-grid">'+''.join('<article class="care-service"><span aria-hidden="true">✦</span><p>'+e(x)+'</p></article>'for x in items)+'</div>')
def appointment(c,address):return section(c['address'],paras([address])+'<a class="button" href="'+BOOK+'">'+e(c['book'])+'</a>','care-location')
def cover(title,intro,image,c):
 return html('<section class="interior-cover care-cover"><img class="fill-img" src="'+e(image)+'" alt=""><div class="interior-cover-gradient" aria-hidden="true"></div><div class="interior-cover-content section-shell"><p class="section-index light">'+e(c['services'])+'</p><h1>'+e(title)+'</h1><p>'+e(intro)+'</p></div></section>')+'\n<!-- wp:dr-ali-moradi/breadcrumbs /-->\n'
def gallery(area):return '<!-- wp:dr-ali-moradi/page-section {"section":"gallery-'+area+'"} /-->'
def make(slug,lang,cover_url,clinic_cover):
 c=COPY[lang];area='clinic'if slug in ('clinic-services','clinic-surgery-care')else'hospital';title=c[area+'Service' if slug.endswith('services')else area+'Title']
 intro=c[area+'Overview']if area=='clinic' and slug.endswith('services')else(c['razaviOverview']if slug=='hospital-services'else c[area+'Intro'])
 image=clinic_cover if area=='clinic'else cover_url
 # Long narrative belongs in the body, keeping the photographic title legible.
 page=cover(title,c[area+'Intro'],image,c)
 body='<div class="section-shell section-space care-editorial">'
 if slug.endswith('services'):
  body+=section(c['razaviTitle']if area=='hospital'else c['clinicService'],paras([intro]))
  body+=tiles(c['minorTitle']if area=='clinic'else c['majorTitle'],c['minor']if area=='clinic'else c['major'])
  page+=html(body+'</div>')+'\n'+gallery(area)+'\n';body='<div class="section-shell section-space care-editorial">'
  body+=section(c['guide'],paras([c['prepare']])+guide(area,c)+'<p><a class="text-link" href="'+url(area+'-surgery-care',lang)+'">'+e(c['readGuide'])+'</a></p>')
  if area=='clinic':body+=tiles(c['outpatientTitle'],c['outpatient'])+appointment(c,c['clinicAddress'])
  else:
   body+=section(c['razaviTitle'],paras([c['coordination']]))+section(c['imamTitle'],paras([c['imamOverview']]))
   body+=section(c['address'],paras([c['razaviAddress'],c['imamAddress'],c['bookingNote']])+'<a class="button" href="'+BOOK+'">'+e(c['book'])+'</a>','care-location')
 else:body+=guide(area,c)+appointment(c,c[area+'Address']if area=='clinic'else c['razaviAddress']+' · '+c['imamAddress'])
 if area=='hospital':body+='<p class="care-photo-credit">Photo: <a href="https://commons.wikimedia.org/wiki/File:Razavihospital_faz2.jpg">PRRazaviHospital</a> · <a href="https://creativecommons.org/licenses/by-sa/4.0/">CC BY-SA 4.0</a> · displayed with a cover crop and colour overlay.</p>'
 page+=html(body+'</div>');return title,page
def main():
 rows=json.loads((WORK/'care-pages-before.json').read_text(encoding='utf8'))
 cover_url=(WORK/'razavi-cover-url.txt').read_text(encoding='utf8').strip()
 out=ROOT/'wordpress-theme/content-migration/clinical-care-2026-10-06';out.mkdir(exist_ok=True)
 ops=[]
 for p in rows:
  lang=p['lang'];c=COPY[lang];old=p['slug'];new={'before-surgery':'hospital-surgery-care','after-surgery':'clinic-surgery-care'}.get(old,old)
  op={'id':p['id'],'type':'page','expected_sha256':p['expected_sha256']}
  if old=='clinical-care':
   story=html('<section class="section-shell section-space care-editorial care-hub">'+section(c['hubTitle'],paras([c['hubIntro'],c['hubPrivate']]))+section(c['benefitTitle'],paras([c['benefit']]))+'<div class="care-location">'+paras([c['hubCta']])+'<a class="button" href="'+BOOK+'">'+e(c['book'])+'</a></div><div class="care-guide-links"><a class="text-link" href="'+url('hospital-surgery-care',lang)+'">'+e(c['hospitalTitle'])+'</a><a class="text-link" href="'+url('clinic-surgery-care',lang)+'">'+e(c['clinicTitle'])+'</a></div><p>'+e(c['choose'])+'</p></section>')
   content=re.sub(r'<!-- wp:html -->\s*<section class="section-shell section-space">.*?<!-- /wp:html -->',lambda _:story,p['content'],count=1,flags=re.S)
   if content==p['content']:raise ValueError('Hub section not found')
  else:
   clinic_cover=re.search(r'<img[^>]*src="([^"]+)"',next(q['content']for q in rows if q['slug']=='clinical-care'and q['lang']==lang)).group(1)
   title,content=make(new,lang,cover_url,clinic_cover)
   op.update(title=title,expected_title=p['title'])
   if new!=old:op.update(slug=new,expected_slug=old)
  op['content']=content
  folder=out/lang;folder.mkdir(exist_ok=True);(folder/(str(p['id'])+'-'+new+'.html')).write_text(content,encoding='utf8');ops.append(op)
 (WORK/'care-update-manifest.json').write_text(json.dumps(ops,ensure_ascii=False),encoding='utf8')
 (out/'copy.json').write_text(json.dumps(COPY,ensure_ascii=False,indent=2),encoding='utf8')
 print('Built',len(ops),'page operations')
if __name__=='__main__':main()
