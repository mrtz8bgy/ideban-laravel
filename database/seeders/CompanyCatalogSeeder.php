<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

/**
 * Company service catalogue for شبکه پردازان ایده‌بان الماس.
 * Describes what the company offers. It contains NO client names, NO project claims,
 * NO certifications and NO official tariffs; every price is shown as a price inquiry.
 * Review the wording before publishing.
 */
class CompanyCatalogSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['slug' => 'passive-active-network', 'name_fa' => 'شبکه پسیو و اکتیو', 'name_en' => 'Passive & active networks', 'sort_order' => 5,
                'description_fa' => 'طراحی و اجرای زیرساخت شبکه؛ از تجهیزات فعال مانند سوئیچ و Access Point تا قفسه‌ها، پچ‌پنل و مدیریت پیکربندی.',
                'description_en' => 'Design and deployment of network infrastructure: active equipment such as switches and access points, plus racks, patch panels and configuration management.',
                'media_path' => 'images/samples/category-passive-active-network.jpg'],
            ['slug' => 'cabling-fiber', 'name_fa' => 'کابل‌کشی و فیبر نوری', 'name_en' => 'Cabling & fiber optics', 'sort_order' => 6,
                'description_fa' => 'کابل‌کشی ساختاریافته مس و فیبر نوری، جوشکاری فیبر، تست و گزارش‌دهی با OTDR برای زیرساخت‌های اداری و صنعتی.',
                'description_en' => 'Structured copper and fiber optic cabling, fiber splicing, and testing and reporting with OTDR for office and industrial sites.',
                'media_path' => 'images/samples/category-cabling-fiber.jpg'],
            ['slug' => 'cctv-security', 'name_fa' => 'دوربین مداربسته و سیستم‌های حفاظتی', 'name_en' => 'CCTV & security systems', 'sort_order' => 7,
                'description_fa' => 'طراحی، نصب و راه‌اندازی دوربین مداربسته IP، ضبط‌کننده شبکه‌ای، کنترل تردد، اعلام سرقت و مشاهده از راه دور.',
                'description_en' => 'Design, installation and commissioning of IP CCTV cameras, network video recorders, access control, intrusion alarms and remote viewing.',
                'media_path' => 'images/samples/category-cctv-security.jpg'],
        ];

        foreach ($categories as $row) {
            ServiceCategory::updateOrCreate(['slug' => $row['slug']], $row + ['is_active' => true]);
        }

        // Existing services (from IdebanCatalogSeeder) are kept; only their featured flag changes.
        Service::whereIn('slug', ['business-website', 'custom-laravel', 'deployment-devops'])->update(['is_featured' => false]);

        $services = [
            [
                'category' => 'cabling-fiber', 'slug' => 'structured-cabling',
                'title_fa' => 'کابل‌کشی ساختاریافته (پسیو)', 'title_en' => 'Structured cabling (passive)',
                'summary_fa' => 'کابل‌کشی مرتب و استاندارد مس برای شبکه‌های اداری، با پچ‌پنل، پریز و مستندسازی کامل.',
                'summary_en' => 'Neat, standards-based copper cabling for office networks, with patch panels, outlets and full documentation.',
                'description_fa' => 'کابل‌کشی پسیو زیربنای هر شبکه پایدار است. ما مسیر کابل‌ها، پریزها، قفسه‌ها و پچ‌پنل‌ها را طراحی می‌کنیم، کابل‌ها را با رعایت شعاع خمش و فاصله از برق اجرا می‌کنیم و در پایان نقشه و برچسب‌گذاری تحویل می‌دهیم.',
                'description_en' => 'Passive cabling is the foundation of a reliable network. We plan cable routes, outlets, racks and patch panels, install cables respecting bend radius and separation from power, and hand over maps and labelling.',
                'included_fa' => ['بازدید و نقشه‌برداری محل', 'کابل‌کشی Cat6 یا Cat6A طبق طراحی', 'نصب پچ‌پنل، پریز و سینی کابل', 'تست پیوستگی و گزارش تست هر نقطه', 'مستندسازی و برچسب‌گذاری'],
                'included_en' => ['Site survey and mapping', 'Cat6 or Cat6A cabling per design', 'Patch panel, outlet and tray installation', 'Continuity testing with a report per point', 'Documentation and labelling'],
                'excluded_fa' => ['خرید تجهیزات فعال مانند سوئیچ (در صورت درخواست، جدا پیشنهاد می‌شود)', 'کابل‌کشی داخل دیوار بتنی بدون هماهنگی با کارفرما'],
                'excluded_en' => ['Purchase of active equipment such as switches (quoted separately on request)', 'Chasing cables through concrete walls without prior agreement'],
                'delivery_days' => 14, 'is_featured' => false, 'media_path' => 'images/samples/service-structured-cabling.jpg',
            ],
            [
                'category' => 'cabling-fiber', 'slug' => 'fiber-optic-installation',
                'title_fa' => 'کابل‌کشی و نصب فیبر نوری', 'title_en' => 'Fiber optic installation',
                'summary_fa' => 'اجرای لینک فیبر نوری بین ساختمان‌ها و طبقات، جوشکاری دقیق و تست OTDR.',
                'summary_en' => 'Fiber optic links between buildings and floors, precision fusion splicing and OTDR testing.',
                'description_fa' => 'برای لینک‌های پرظرفیت و مقاوم در برابر تداخل الکترومغناطیسی از فیبر نوری استفاده می‌کنیم. جوشکاری فیبر با دستگاه فیوژن‌اسپلایسر انجام می‌شود و هر لینک با OTDR تست و گزارش‌دهی می‌شود.',
                'description_en' => 'We use fiber optics for high-capacity links that resist electromagnetic interference. Fibers are fusion-spliced with a precision splicer, and every link is tested and reported with an OTDR.',
                'included_fa' => ['نصب کابل فیبر نوری تک‌مود یا چندمود', 'جوشکاری فیبر (Fusion Splicing)', 'نصب ODF، پیگتیل و پچ‌کورد', 'تست OTDR و گزارش تضعیف هر لینک', 'مستندسازی مسیر و هسته‌ها'],
                'included_en' => ['Single-mode or multi-mode fiber installation', 'Fusion splicing', 'ODF, pigtail and patch cord installation', 'OTDR testing and loss report per link', 'Route and core documentation'],
                'excluded_fa' => ['خرید کابل و تجهیزات فیبر (به‌صورت جداگانه پیشنهاد می‌شود)'],
                'excluded_en' => ['Purchase of fiber cable and equipment (quoted separately)'],
                'delivery_days' => 21, 'is_featured' => true, 'media_path' => 'images/samples/service-fiber-splicing.jpg',
            ],
            [
                'category' => 'passive-active-network', 'slug' => 'active-network-design',
                'title_fa' => 'طراحی و پیاده‌سازی شبکه اکتیو', 'title_en' => 'Active network design & deployment',
                'summary_fa' => 'طراحی شبکه با سوئیچ‌های مدیریتی، VLAN، Wi-Fi سازمانی و تفکیک ترافیک کاری و مهمان.',
                'summary_en' => 'Network design with managed switches, VLANs, enterprise Wi-Fi and separation of staff and guest traffic.',
                'description_fa' => 'شبکه اکتیو مغز ارتباطات سازمان است. ما بر اساس نیاز، معماری لایه‌ای، VLAN، مسیریابی و سیاست‌های دسترسی را طراحی و پیکربندی می‌کنیم و Access Pointها را با پوشش مناسب نصب می‌کنیم.',
                'description_en' => 'The active network is the core of organisational communication. We design and configure a layered architecture with VLANs, routing and access policies, and install access points with proper coverage.',
                'included_fa' => ['نقشه پوشش Wi-Fi و نقطه‌یابی', 'پیکربندی سوئیچ‌های مدیریتی و VLAN', 'تفکیک شبکه کارکنان، مهمان و دوربین', 'راه‌اندازی Access Point و کنترلر', 'مستندسازی طرح IP و پیکربندی'],
                'included_en' => ['Wi-Fi coverage map and site survey', 'Managed switch and VLAN configuration', 'Separation of staff, guest and camera networks', 'Access point and controller setup', 'IP plan and configuration documentation'],
                'excluded_fa' => ['خرید سخت‌افزار (بر اساس نیاز و بودجه پیشنهاد می‌شود)'],
                'excluded_en' => ['Hardware purchase (recommended based on need and budget)'],
                'delivery_days' => 21, 'is_featured' => true, 'media_path' => 'images/samples/service-wifi-switching.jpg',
            ],
            [
                'category' => 'passive-active-network', 'slug' => 'network-monitoring',
                'title_fa' => 'پایش و مدیریت شبکه', 'title_en' => 'Network monitoring & management',
                'summary_fa' => 'پایش لحظه‌ای تجهیزات، هشدار خرابی و گزارش عملکرد با ابزارهای متن‌باز و تجاری.',
                'summary_en' => 'Real-time device monitoring, failure alerts and performance reports using open-source and commercial tools.',
                'description_fa' => 'پایش شبکه باعث می‌شود قبل از قطع شدن سرویس، مشکل را ببینید. ما سوئیچ‌ها، فایروال‌ها و سرورها را با ابزارهایی مانند Zabbix، Prometheus و Grafana پایش می‌کنیم و هشدارها را به تیم شما می‌فرستیم.',
                'description_en' => 'Monitoring lets you see problems before services fail. We monitor switches, firewalls and servers with tools such as Zabbix, Prometheus and Grafana, and send alerts to your team.',
                'included_fa' => ['نصب و پیکربندی Zabbix یا Prometheus', 'داشبورد Grafana با شاخص‌های کلیدی', 'قانون هشدار (CPU، پهنای باند، دسترسی)', 'گزارش ماهانه عملکرد'],
                'included_en' => ['Zabbix or Prometheus setup', 'Grafana dashboards with key indicators', 'Alert rules (CPU, bandwidth, availability)', 'Monthly performance report'],
                'excluded_fa' => ['پشتیبانی ۲۴ ساعته (در قرارداد نگهداری جداگانه ارائه می‌شود)'],
                'excluded_en' => ['24/7 on-call support (offered under a separate maintenance contract)'],
                'delivery_days' => 14, 'is_featured' => false, 'media_path' => null,
            ],
            [
                'category' => 'cctv-security', 'slug' => 'cctv-installation',
                'title_fa' => 'نصب دوربین مداربسته (CCTV)', 'title_en' => 'CCTV camera installation',
                'summary_fa' => 'طراحی و نصب دوربین‌های IP با ضبط‌کننده شبکه‌ای، مشاهده از موبایل و ذخیره‌سازی مطمئن.',
                'summary_en' => 'IP camera design and installation with network video recorders, mobile viewing and reliable storage.',
                'description_fa' => 'طراحی دوربین از زاویه دید و نور محیط شروع می‌شود. ما دوربین‌های IP، ضبط‌کننده NVR و ذخیره‌سازی را انتخاب، نصب و تنظیم می‌کنیم تا تصویر واضح، مدت نگهداری مناسب و دسترسی امن داشته باشید.',
                'description_en' => 'Camera design starts with field of view and lighting. We select, install and configure IP cameras, NVRs and storage so you get clear images, suitable retention and secure access.',
                'included_fa' => ['طراحی زاویه دید و پوشش دوربین', 'نصب دوربین IP و کابل‌کشی اختصاصی', 'راه‌اندازی NVR و تنظیم مدت نگهداری تصویر', 'دسترسی امن از موبایل و کامپیوتر', 'آموزش کاربری و تحویل'],
                'included_en' => ['Field-of-view and coverage design', 'IP camera installation with dedicated cabling', 'NVR setup and retention configuration', 'Secure access from mobile and computer', 'User training and handover'],
                'excluded_fa' => ['خرید دوربین و هارد ذخیره‌سازی (بر اساس نیاز و بودجه پیشنهاد می‌شود)'],
                'excluded_en' => ['Purchase of cameras and storage drives (recommended based on need and budget)'],
                'delivery_days' => 14, 'is_featured' => true, 'media_path' => 'images/samples/service-ip-camera-nvr.jpg',
            ],
            [
                'category' => 'cctv-security', 'slug' => 'access-control-alarm',
                'title_fa' => 'کنترل تردد و سیستم اعلام سرقت', 'title_en' => 'Access control & intrusion alarm',
                'summary_fa' => 'کارت‌خوان و قفل الکترونیکی، سنسور حرکت، آژیر و اعلام به موبایل برای ساختمان‌های اداری و صنعتی.',
                'summary_en' => 'Card readers, electronic locks, motion sensors, sirens and mobile alerts for office and industrial buildings.',
                'description_fa' => 'کنترل تردد و اعلام سرقت لایه‌های امنیت فیزیکی ساختمان هستند. ما درب‌ها، کارت‌خوان‌ها، سنسورها و پنل اعلام را طراحی و پیکربندی می‌کنیم و گزارش ورود و خروج را در اختیار شما می‌گذاریم.',
                'description_en' => 'Access control and intrusion alarms form the physical security layer of a building. We design and configure doors, readers, sensors and alarm panels, and provide entry and exit reports.',
                'included_fa' => ['طراحی نقشه درب‌ها و مسیر تردد', 'نصب کارت‌خوان و قفل الکترونیکی', 'نصب سنسور حرکت و پنل اعلام سرقت', 'اعلام وضعیت به موبایل', 'گزارش ورود و خروج'],
                'included_en' => ['Door and access route plan', 'Card reader and electric lock installation', 'Motion sensor and alarm panel installation', 'Status alerts to mobile', 'Entry and exit reports'],
                'excluded_fa' => ['تأمین کارت‌های مغناطیسی و RFID به تعداد مورد نیاز (جداگانه پیشنهاد می‌شود)'],
                'excluded_en' => ['Supply of magnetic or RFID cards in required quantities (quoted separately)'],
                'delivery_days' => 21, 'is_featured' => false, 'media_path' => 'images/samples/service-access-alarm.jpg',
            ],
            [
                'category' => 'network-security', 'slug' => 'network-security-audit',
                'title_fa' => 'ممیزی امنیت شبکه و تست نفوذ', 'title_en' => 'Network security audit & penetration testing',
                'summary_fa' => 'اسکن آسیب‌پذیری، بررسی پیکربندی و گزارش اولویت‌بندی‌شده اصلاحات. تست نفوذ فقط با مجوز کتبی.',
                'summary_en' => 'Vulnerability scanning, configuration review and a prioritised remediation report. Penetration testing only with written authorisation.',
                'description_fa' => 'ممیزی امنیت به شما نشان می‌دهد کجای شبکه آسیب‌پذیر است. ما با ابزارهایی مانند Nmap، OpenVAS و Burp Suite اسکن و بررسی انجام می‌دهیم و گزارشی با اولویت اصلاح ارائه می‌کنیم. تست نفوذ تنها با مجوز کتبی و محدوده تعیین‌شده انجام می‌شود.',
                'description_en' => 'A security audit shows where your network is exposed. We scan and review with tools such as Nmap, OpenVAS and Burp Suite, and deliver a remediation report by priority. Penetration tests run only with written authorisation and a defined scope.',
                'included_fa' => ['اسکن آسیب‌پذیری (Nmap، OpenVAS)', 'بررسی پیکربندی سوئیچ، فایروال و سرور', 'تست نفوذ محدود با مجوز کتبی (در صورت درخواست)', 'گزارش اولویت‌بندی‌شده با راهکار اصلاح'],
                'included_en' => ['Vulnerability scanning (Nmap, OpenVAS)', 'Review of switch, firewall and server configuration', 'Scoped penetration testing with written authorisation (on request)', 'Prioritised report with remediation steps'],
                'excluded_fa' => ['اجرای اصلاحات (در صورت درخواست، جداگانه پیشنهاد می‌شود)'],
                'excluded_en' => ['Implementation of fixes (quoted separately on request)'],
                'delivery_days' => 14, 'is_featured' => false, 'media_path' => null,
            ],
            [
                'category' => 'network-security', 'slug' => 'firewall-vpn',
                'title_fa' => 'فایروال، VPN و امنیت لبه شبکه', 'title_en' => 'Firewall, VPN & edge security',
                'summary_fa' => 'پیکربندی فایروال، دسترسی امن از راه دور با VPN و قوانین دسترسی بر اساس نیاز سازمان.',
                'summary_en' => 'Firewall configuration, secure remote access with VPN and access rules based on organisational needs.',
                'description_fa' => 'مرز شبکه شما باید کنترل‌شده باشد. ما فایروال را بر اساس تجهیزات موجود یا پیشنهادی (مانند pfSense، MikroTik یا FortiGate) پیکربندی می‌کنیم، VPN امن برای کارکنان دوردست برقرار می‌کنیم و قوانین را مستند می‌کنیم.',
                'description_en' => 'Your network perimeter must be controlled. We configure firewalls on existing or recommended equipment such as pfSense, MikroTik or FortiGate, set up secure VPN access for remote staff, and document the rules.',
                'included_fa' => ['پیکربندی فایروال و قوانین دسترسی', 'VPN امن برای دسترسی از راه دور', 'تفکیک ناحیه‌های امنیتی (DMZ، داخلی، مهمان)', 'به‌روزرسانی امن فریم‌ور', 'مستندسازی قوانین'],
                'included_en' => ['Firewall and access rule configuration', 'Secure VPN for remote access', 'Security zone separation (DMZ, internal, guest)', 'Secure firmware updates', 'Rule documentation'],
                'excluded_fa' => ['خرید فایروال یا سرور VPN (بر اساس نیاز پیشنهاد می‌شود)'],
                'excluded_en' => ['Purchase of firewall or VPN hardware (recommended based on need)'],
                'delivery_days' => 14, 'is_featured' => false, 'media_path' => null,
            ],
            [
                'category' => 'devops-infrastructure', 'slug' => 'devops-pipeline',
                'title_fa' => 'DevOps: CI/CD، کانتینر و پایش', 'title_en' => 'DevOps: CI/CD, containers & monitoring',
                'summary_fa' => 'خط انتشار خودکار با Git و Docker، مدیریت پیکربندی با Ansible و پایش با Prometheus و Grafana.',
                'summary_en' => 'Automated release pipelines with Git and Docker, configuration management with Ansible and monitoring with Prometheus and Grafana.',
                'description_fa' => 'DevOps یعنی انتشار امن و تکرارپذیر. ما خط CI/CD با GitLab CI یا GitHub Actions می‌سازیم، سرویس‌ها را در Docker کانتینری می‌کنیم، پیکربندی سرورها را با Ansible یکسان نگه می‌داریم و پایش را فعال می‌کنیم.',
                'description_en' => 'DevOps means secure, repeatable releases. We build CI/CD pipelines with GitLab CI or GitHub Actions, containerise services with Docker, keep server configuration consistent with Ansible, and enable monitoring.',
                'included_fa' => ['خط CI/CD با GitLab CI یا GitHub Actions', 'کانتینری‌سازی با Docker و Docker Compose', 'مدیریت پیکربندی سرورها با Ansible', 'پایش با Prometheus و Grafana', 'مستندسازی فرایند انتشار'],
                'included_en' => ['CI/CD pipeline with GitLab CI or GitHub Actions', 'Containerisation with Docker and Docker Compose', 'Server configuration management with Ansible', 'Monitoring with Prometheus and Grafana', 'Release process documentation'],
                'excluded_fa' => ['هزینه سرور ابری و سرویس‌های شخص ثالث'],
                'excluded_en' => ['Cloud server and third-party service fees'],
                'delivery_days' => 21, 'is_featured' => false, 'media_path' => null,
            ],
            [
                'category' => 'web-software', 'slug' => 'enterprise-software',
                'title_fa' => 'تولید نرم‌افزار سازمانی و API', 'title_en' => 'Enterprise software & API development',
                'summary_fa' => 'نرم‌افزارهای داخلی، داشبورد مدیریتی و APIهای اتصال به سامانه‌های موجود.',
                'summary_en' => 'Internal business software, management dashboards and APIs that connect to existing systems.',
                'description_fa' => 'وقتی نرم‌افزار آماده‌ای جوابگو نیست، نرم‌افزار سفارشی می‌سازیم. کار از تحلیل نیاز و نمونه اولیه شروع می‌شود و با تست، استقرار و آموزش ادامه پیدا می‌کند. نرم‌افزارها معمولاً با Laravel، MySQL و React یا Vue ساخته می‌شوند.',
                'description_en' => 'When off-the-shelf software is not enough, we build custom software. Work starts with requirements and a prototype, then testing, deployment and training. We typically build with Laravel, MySQL and React or Vue.',
                'included_fa' => ['تحلیل نیاز و نمونه اولیه', 'توسعه با Laravel و MySQL', 'طراحی رابط کاربری فارسی و انگلیسی', 'APIهای اتصال به سامانه‌های موجود', 'تست، استقرار و آموزش کاربران'],
                'included_en' => ['Requirements analysis and prototype', 'Development with Laravel and MySQL', 'Bilingual Persian and English interface design', 'APIs to connect existing systems', 'Testing, deployment and user training'],
                'excluded_fa' => ['هزینه میزبانی و دامنه (جداگانه محاسبه می‌شود)'],
                'excluded_en' => ['Hosting and domain costs (calculated separately)'],
                'delivery_days' => 45, 'is_featured' => false, 'media_path' => null,
            ],
        ];

        foreach ($services as $row) {
            $category = ServiceCategory::where('slug', $row['category'])->firstOrFail();
            $data = $row;
            unset($data['category']);
            $data['category_id'] = $category->id;
            $data['is_active'] = true;
            Service::updateOrCreate(['slug' => $row['slug']], $data);
        }
    }
}
