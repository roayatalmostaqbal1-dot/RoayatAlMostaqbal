const mockData = {
    user: {
        name: "رباح السويدي",
        emiratesId: "784-1980-1234567-1",
        nationality: "إماراتي",
        expiryDate: "2030-12-31",
        securityLevel: "عالي"
    },
    
    securityLogs: [
        { time: "14:30", event: "تسجيل دخول ناجح", source: "192.168.1.10", severity: "low" },
        { time: "14:25", event: "محاولة وصول غير مصرح", source: "10.0.0.45", severity: "high" },
        { time: "14:20", event: "فحص نظام تلقائي", source: "localhost", severity: "medium" },
        { time: "14:15", event: "وصول إلى بيانات حساسة", source: "192.168.1.15", severity: "medium" },
        { time: "14:10", event: "تحذير من برمجية خبيثة", source: "مصدر خارجي", severity: "critical" },
        { time: "14:05", event: "تحديث نظام أمني", source: "سيرفر التحديثات", severity: "low" },
        { time: "14:00", event: "فحص نقاط الضعف", source: "أداة المسح", severity: "medium" },
        { time: "13:55", event: "إنشاء نسخة احتياطية", source: "نظام النسخ", severity: "low" },
        { time: "13:50", event: "تغيير سياسة الوصول", source: "المسؤول", severity: "high" },
        { time: "13:45", event: "تسجيل دخول من جهاز جديد", source: "192.168.1.25", severity: "medium" },
        { time: "13:40", event: "فحص التشفير", source: "أداة التشفير", severity: "low" },
        { time: "13:35", event: "إنذار تجاوز النطاق", source: "جدار الحماية", severity: "high" }
    ],
    
    riskLevels: [
        { level: "منخفض", color: "#00aa00", width: "25%", description: "الوضع طبيعي - لا توجد تهديدات نشطة" },
        { level: "متوسط", color: "#ffaa00", width: "50%", description: "يوجد بعض الأنشطة غير المعتادة - يوصى بالمراجعة" },
        { level: "عالي", color: "#cc0000", width: "75%", description: "تهديدات أمنية محتملة - مطلوب تدخل" },
        { level: "حرج", color: "#dc3545", width: "95%", description: "تهديد أمني حرج - تدخل فوري مطلوب" }
    ],
    
    recommendations: [
        "الوضع طبيعي - لا إجراء مطلوب.",
        "يوصى بمراجعة سياسات الوصول والتحقق من أذونات المستخدمين.",
        "مطلوب فحص شامل للنظام ومراجعة سجلات الأمان.",
        "تحذير! نظام معرض للخطر - مطلوب تدخل فوري لفريق الأمن السيبراني."
    ],
    
    threatIndicators: [
        { type: "تسجيلات دخول", count: 42, level: "low" },
        { type: "إنذارات", count: 8, level: "medium" },
        { type: "محاولات وصول", count: 3, level: "high" },
        { type: "تهديدات حرجة", count: 1, level: "critical" }
    ]
};

// المتغيرات العامة
let currentRiskIndex = 1; // بداية بمستوى متوسط

// تهيئة التطبيق
document.addEventListener('DOMContentLoaded', function() {
    initApp();
});

function initApp() {
    // ربط الأحداث
    document.getElementById('loginBtn').addEventListener('click', handleLogin);
    document.getElementById('logoutBtn').addEventListener('click', handleLogout);
    document.getElementById('generateInsightBtn').addEventListener('click', generateSecurityInsight);
    document.getElementById('refreshLogsBtn').addEventListener('click', refreshSecurityLogs);
    document.getElementById('generateLogsBtn').addEventListener('click', generateNewLogs);
    document.getElementById('exportLogsBtn').addEventListener('click', showExportModal);
    document.getElementById('downloadCsvBtn').addEventListener('click', downloadCsv);
    
    // إغلاق النافذة المنبثقة
    document.querySelector('.modal-close').addEventListener('click', function() {
        document.getElementById('exportModal').classList.remove('active');
    });
    
    // إغلاق النافذة المنبثقة بالنقر خارجها
    document.getElementById('exportModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
        }
    });
    
    // زر التصفية
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            filterLogs(this.textContent);
        });
    });
    
    // عرض البيانات الأولية
    loadSecurityLogs();
    updateThreatIndicators();
    updateRiskMeter();
}

// تسجيل الدخول
function handleLogin() {
    const loginBtn = document.getElementById('loginBtn');
    const originalText = loginBtn.innerHTML;
    
    loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحقق...';
    loginBtn.disabled = true;
    
    setTimeout(() => {
        document.getElementById('loginScreen').classList.remove('active');
        document.getElementById('dashboardScreen').classList.add('active');
        
        loginBtn.innerHTML = originalText;
        loginBtn.disabled = false;
        
        document.getElementById('logCount').textContent = `${mockData.securityLogs.length} سجل`;
    }, 1500);
}

// تسجيل الخروج
function handleLogout() {
    document.getElementById('dashboardScreen').classList.remove('active');
    document.getElementById('loginScreen').classList.add('active');
}

// تحميل سجلات الأمان
function loadSecurityLogs() {
    const tbody = document.getElementById('logsTableBody');
    tbody.innerHTML = '';
    
    mockData.securityLogs.forEach(log => {
        const row = document.createElement('tr');
        
        let severityClass = '';
        switch(log.severity) {
            case 'low': severityClass = 'severity-low'; break;
            case 'medium': severityClass = 'severity-medium'; break;
            case 'high': severityClass = 'severity-high'; break;
            case 'critical': severityClass = 'severity-critical'; break;
        }
        
        row.innerHTML = `
            <td>${log.time}</td>
            <td>${log.event}</td>
            <td>${log.source}</td>
            <td class="${severityClass}">${getSeverityText(log.severity)}</td>
        `;
        
        tbody.appendChild(row);
    });
}

// الحصول على نص مستوى الخطورة
function getSeverityText(severity) {
    switch(severity) {
        case 'low': return 'منخفض';
        case 'medium': return 'متوسط';
        case 'high': return 'عالي';
        case 'critical': return 'حرج';
        default: return 'غير معروف';
    }
}

// تحديث مؤشرات التهديد
function updateThreatIndicators() {
    const indicators = document.querySelector('.indicators-grid');
    indicators.innerHTML = '';
    
    mockData.threatIndicators.forEach(indicator => {
        const indicatorDiv = document.createElement('div');
        indicatorDiv.className = `indicator ${indicator.level}`;
        
        let icon = '';
        switch(indicator.type) {
            case 'تسجيلات دخول': icon = 'fa-user-check'; break;
            case 'إنذارات': icon = 'fa-exclamation-triangle'; break;
            case 'محاولات وصول': icon = 'fa-skull-crossbones'; break;
            case 'تهديدات حرجة': icon = 'fa-fire'; break;
        }
        
        indicatorDiv.innerHTML = `
            <i class="fas ${icon}"></i>
            <span>${indicator.type}</span>
            <strong>${indicator.count}</strong>
        `;
        
        indicators.appendChild(indicatorDiv);
    });
}

// تحديث عداد الخطورة
function updateRiskMeter() {
    const riskData = mockData.riskLevels[currentRiskIndex];
    
    document.getElementById('currentRiskLevel').textContent = riskData.level;
    document.getElementById('currentRiskLevel').className = `risk-level risk-${riskData.level === 'منخفض' ? 'low' : riskData.level === 'متوسط' ? 'medium' : riskData.level === 'عالي' ? 'high' : 'critical'}`;
    document.getElementById('riskFill').style.width = riskData.width;
    document.getElementById('riskFill').style.background = riskData.color;
    
    document.getElementById('currentRecommendation').innerHTML = `
        <i class="fas fa-lightbulb"></i>
        <p>${mockData.recommendations[currentRiskIndex]}</p>
    `;
}

// توليد تحليل أمني جديد
function generateSecurityInsight() {
    const btn = document.getElementById('generateInsightBtn');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحليل...';
    btn.disabled = true;
    
    setTimeout(() => {
        const random = Math.random();
        if (random < 0.5) {
            currentRiskIndex = 0; // منخفض
        } else if (random < 0.8) {
            currentRiskIndex = 1; // متوسط
        } else if (random < 0.95) {
            currentRiskIndex = 2; // عالي
        } else {
            currentRiskIndex = 3; // حرج
        }
        
        updateRiskMeter();
        
        const now = new Date();
        const timeString = `${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}`;
        
        const events = [
            { event: "تحليل ذكاء اصطناعي مكتمل", severity: "low" },
            { event: "اكتشاف نمط غير عادي", severity: "medium" },
            { event: "تم تحديد تهديد محتمل", severity: "high" },
            { event: "تم اكتشاف تهديد حرج", severity: "critical" }
        ];
        
        const newEvent = events[currentRiskIndex];
        
        mockData.securityLogs.unshift({
            time: timeString,
            event: newEvent.event,
            source: "نظام SADD AI",
            severity: newEvent.severity
        });
        
        loadSecurityLogs();
        
        if (currentRiskIndex >= 2) {
            mockData.threatIndicators[2].count++;
            if (currentRiskIndex === 3) {
                mockData.threatIndicators[3].count++;
            }
        }
        updateThreatIndicators();
        
        document.getElementById('logCount').textContent = `${mockData.securityLogs.length} سجل`;
        
        btn.innerHTML = originalText;
        btn.disabled = false;
        
        btn.style.transform = 'scale(0.95)';
        setTimeout(() => {
            btn.style.transform = 'scale(1)';
        }, 150);
        
    }, 2000);
}

// تصفية السجلات
function filterLogs(filter) {
    loadSecurityLogs();
}

// تحديث السجلات
function refreshSecurityLogs() {
    const btn = document.getElementById('refreshLogsBtn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحديث...';
    
    setTimeout(() => {
        loadSecurityLogs();
        btn.innerHTML = '<i class="fas fa-redo"></i> تحديث';
        
        btn.style.transform = 'rotate(360deg)';
        setTimeout(() => {
            btn.style.transform = 'rotate(0deg)';
        }, 300);
    }, 1000);
}

// توليد سجلات جديدة
function generateNewLogs() {
    const events = [
        { event: "فحص أمني روتيني", severity: "low" },
        { event: "تحديث قاعدة بيانات التهديدات", severity: "low" },
        { event: "مراجعة سياسات الأمان", severity: "medium" },
        { event: "تحذير من هجوم محتمل", severity: "high" },
        { event: "تسجيل دخول من موقع غير معتاد", severity: "medium" },
        { event: "فحص نقاط الضعف", severity: "medium" }
    ];
    
    const randomEvent = events[Math.floor(Math.random() * events.length)];
    const now = new Date();
    const timeString = `${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}`;
    
    mockData.securityLogs.unshift({
        time: timeString,
        event: randomEvent.event,
        source: "نظام المراقبة",
        severity: randomEvent.severity
    });
    
    loadSecurityLogs();
    document.getElementById('logCount').textContent = `${mockData.securityLogs.length} سجل`;
    
    const btn = document.getElementById('generateLogsBtn');
    btn.style.background = 'var(--success-color)';
    btn.style.color = 'white';
    setTimeout(() => {
        btn.style.background = '';
        btn.style.color = '';
    }, 500);
}

// عرض نافذة التصدير
function showExportModal() {
    document.getElementById('exportModal').classList.add('active');
}

// تنزيل ملف CSV
function downloadCsv() {
    let csvContent = "timestamp,event_type,source,severity\n";
    
    mockData.securityLogs.forEach(log => {
        const row = [
            `2026-01-08 ${log.time}:00`,
            log.event,
            log.source,
            log.severity
        ].join(',');
        
        csvContent += row + "\n";
    });
    
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.setAttribute('href', url);
    link.setAttribute('download', `sadd-security-logs-${new Date().toISOString().split('T')[0]}.csv`);
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    const btn = document.getElementById('downloadCsvBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check"></i> تم التنزيل!';
    
    setTimeout(() => {
        btn.innerHTML = originalText;
        document.getElementById('exportModal').classList.remove('active');
    }, 1500);
}
