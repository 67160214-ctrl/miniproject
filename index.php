<?php
// =========================================================
// PALM AI - Smart Quality Inspection Storytelling Dashboard
// รายวิชา: Business Idea Creation
// =========================================================

require_once 'db.php';

try {
    // 1. Fetch KPI Metrics
    $kpi_stmt = $pdo->query("SELECT 
        COUNT(id) as total_batches,
        SUM(weight_tons) as total_weight,
        AVG(estimated_oer_pct) as avg_oer,
        AVG(ai_confidence_score) as avg_confidence,
        SUM(CASE WHEN quality_grade IN ('Grade A+', 'Grade A') THEN 1 ELSE 0 END) as premium_count
    FROM palm_inspections");
    $kpi = $kpi_stmt->fetch();

    // 2. Fetch Quality Breakdown
    $quality_stmt = $pdo->query("SELECT 
        AVG(ripeness_ripe_pct) as avg_ripe,
        AVG(ripeness_unripe_pct) as avg_unripe,
        AVG(ripeness_overripe_pct) as avg_overripe
    FROM palm_inspections");
    $quality = $quality_stmt->fetch();

    // 3. Fetch Supplier Ranking
    $supplier_stmt = $pdo->query("SELECT 
        supplier_name,
        COUNT(id) as batch_count,
        SUM(weight_tons) as total_weight,
        AVG(estimated_oer_pct) as avg_oer,
        AVG(ripeness_unripe_pct) as avg_unripe
    FROM palm_inspections 
    GROUP BY supplier_name 
    ORDER BY avg_oer DESC");
    $suppliers = $supplier_stmt->fetchAll();

    // 4. Fetch Inspection Logs
    $list_stmt = $pdo->query("SELECT * FROM palm_inspections ORDER BY timestamp DESC LIMIT 10");
    $inspections = $list_stmt->fetchAll();

} catch (PDOException $e) {
    die("Error Querying Data: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PALM AI - Quality Storytelling Dashboard</title>
    <!-- Tailwind CSS & Google Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js & Lucide Icons -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Prompt', sans-serif; background-color: #0f172a; color: #f8fafc; }
        .glass-card { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.08); }
    </style>
</head>
<body class="min-h-screen pb-12">

    <!-- Header Navigation -->
    <header class="border-b border-slate-800 bg-slate-900/80 sticky top-0 z-50 backdrop-blur">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-emerald-500/20 text-emerald-400 rounded-xl border border-emerald-500/30">
                    <i data-lucide="scan-face" class="w-6 h-6"></i>
                </div>
                <div>
                    <h1 class="font-bold text-xl text-white tracking-wide">PALM AI Inspection</h1>
                    <p class="text-xs text-slate-400">Business Idea Creation • Executive Storytelling Dashboard</p>
                </div>
            </div>
            <div>
                <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs font-medium flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> DB: Connected (s67160214)
                </span>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 mt-8 space-y-8">

        <!-- STORY SECTION 1: Executive Summary -->
        <section class="glass-card rounded-2xl p-6 border-l-4 border-l-emerald-500">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-emerald-400">Chapter 1: ภาพรวมสถานการณ์วัตถุดิบ</span>
                <h2 class="text-2xl font-bold text-white mt-1">ยกระดับ OER และกำไรโรงงานด้วยระบบคัดเกรดผลปาล์ม AI</h2>
                <p class="text-slate-400 text-sm mt-1 max-w-3xl">
                    ปัญหาหลักของโรงงานสกัดน้ำมันปาล์มคือปาล์มดิบปะปน ซึ่งลดอัตราการสกัดน้ำมัน (OER) ระบบ PALM AI ช่วยคัดแยกและวิเคราะห์สัดส่วนความสุกของผลปาล์มแบบ Real-time ก่อนเข้าสายการผลิต
                </p>
            </div>

            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-700/50">
                    <p class="text-xs text-slate-400">ปริมาณรับเข้าสะสม</p>
                    <p class="text-2xl font-bold text-white mt-1"><?= number_format($kpi['total_weight'] ?? 0, 2) ?> <span class="text-xs text-slate-400 font-normal">ตัน</span></p>
                    <span class="text-[11px] text-emerald-400 mt-2 block flex items-center gap-1">
                        <i data-lucide="truck" class="w-3.5 h-3.5"></i> จาก <?= $kpi['total_batches'] ?? 0 ?> เที่ยวรถ
                    </span>
                </div>
                <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-700/50">
                    <p class="text-xs text-slate-400">ประมาณการ OER เฉลี่ย</p>
                    <p class="text-2xl font-bold text-emerald-400 mt-1"><?= number_format($kpi['avg_oer'] ?? 0, 2) ?>%</p>
                    <span class="text-[11px] text-slate-400 mt-2 block">เป้าหมายโรงงานมาตรฐาน > 18.00%</span>
                </div>
                <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-700/50">
                    <p class="text-xs text-slate-400">สัดส่วนเกรดพรีเมียม (A/A+)</p>
                    <p class="text-2xl font-bold text-amber-400 mt-1">
                        <?= round((($kpi['premium_count'] ?? 0) / max($kpi['total_batches'] ?? 1, 1)) * 100, 1) ?>%
                    </p>
                    <span class="text-[11px] text-slate-400 mt-2 block"><?= $kpi['premium_count'] ?? 0 ?> จาก <?= $kpi['total_batches'] ?? 0 ?> ล็อต</span>
                </div>
                <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-700/50">
                    <p class="text-xs text-slate-400">ความแม่นยำ AI Confidence</p>
                    <p class="text-2xl font-bold text-cyan-400 mt-1"><?= number_format($kpi['avg_confidence'] ?? 0, 1) ?>%</p>
                    <span class="text-[11px] text-cyan-400/80 mt-2 block">Computer Vision Model v2.4</span>
                </div>
            </div>
        </section>

        <!-- STORY SECTION 2: Analytics & Visual Storytelling -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Chart 1: Quality Composition -->
            <section class="glass-card rounded-2xl p-6 lg:col-span-1 flex flex-col justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-amber-400">Chapter 2: โครงสร้างความสุก</span>
                    <h3 class="text-lg font-bold text-white mt-1">สัดส่วนความสุกของผลปาล์ม</h3>
                </div>
                <div class="my-6 relative flex justify-center">
                    <canvas id="ripenessChart" class="max-h-[220px]"></canvas>
                </div>
                <div class="text-xs text-slate-400 bg-slate-800/40 p-3 rounded-xl border border-slate-700/40">
                    <span class="text-amber-400 font-medium">Insight:</span> ปาล์มดิบส่งผลให้ค่า OER ลดลงอย่างมาก การควบคุมปาล์มดิบให้อยู่ต่ำกว่า 10% จะช่วยเพิ่มกำไรได้ถึง 12%
                </div>
            </section>

            <!-- Chart 2: Supplier Comparison -->
            <section class="glass-card rounded-2xl p-6 lg:col-span-2 flex flex-col justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-cyan-400">Chapter 3: การประเมินคู่ค้า</span>
                    <h3 class="text-lg font-bold text-white mt-1">เปรียบเทียบคุณภาพวัตถุดิบแยกตามลานเท (Supplier Ranking)</h3>
                </div>
                <div class="my-4">
                    <canvas id="supplierChart" class="max-h-[240px]"></canvas>
                </div>
                <div class="text-xs text-slate-400 bg-slate-800/40 p-3 rounded-xl border border-slate-700/40 flex items-center gap-2">
                    <i data-lucide="info" class="w-4 h-4 text-cyan-400 flex-shrink-0"></i>
                    <span>ลานเทที่มี OER สูงสุดจะได้รับโบนัสส่วนต่างราคา เพื่อสร้างแรงจูงใจในการเก็บเกี่ยวปาล์มสุกคุณภาพดี</span>
                </div>
            </section>
        </div>

        <!-- STORY SECTION 3: Detailed Inspection Logs -->
        <section class="glass-card rounded-2xl p-6">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-emerald-400">Chapter 4: บันทึกการตรวจรับ Real-time</span>
                    <h3 class="text-lg font-bold text-white mt-1">ตารางข้อมูลการตรวจรับผลปาล์มด้วย AI</h3>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-800/80 text-slate-300 uppercase font-semibold text-[11px] tracking-wider border-b border-slate-700">
                        <tr>
                            <th class="p-3">Batch ID</th>
                            <th class="p-3">วัน-เวลา</th>
                            <th class="p-3">ลานเท / ผู้ส่ง</th>
                            <th class="p-3">ทะเบียนรถ</th>
                            <th class="p-3 text-right">น้ำหนัก (ตัน)</th>
                            <th class="p-3 text-center">สุก / ดิบ / ร่วง (%)</th>
                            <th class="p-3 text-right">Est. OER %</th>
                            <th class="p-3 text-center">เกรด AI</th>
                            <th class="p-3 text-center">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        <?php foreach ($inspections as $item): ?>
                        <tr class="hover:bg-slate-800/40 transition">
                            <td class="p-3 font-mono font-medium text-slate-200"><?= htmlspecialchars($item['batch_id']) ?></td>
                            <td class="p-3 text-slate-400"><?= date('Y-m-d H:i', strtotime($item['timestamp'])) ?></td>
                            <td class="p-3 font-medium text-white"><?= htmlspecialchars($item['supplier_name']) ?></td>
                            <td class="p-3 text-slate-400"><?= htmlspecialchars($item['truck_license']) ?></td>
                            <td class="p-3 text-right font-semibold text-slate-200"><?= number_format($item['weight_tons'], 2) ?></td>
                            <td class="p-3 text-center">
                                <span class="text-emerald-400"><?= round($item['ripeness_ripe_pct']) ?>%</span> / 
                                <span class="text-rose-400"><?= round($item['ripeness_unripe_pct']) ?>%</span> / 
                                <span class="text-amber-400"><?= round($item['ripeness_overripe_pct']) ?>%</span>
                            </td>
                            <td class="p-3 text-right font-bold text-emerald-400"><?= number_format($item['estimated_oer_pct'], 2) ?>%</td>
                            <td class="p-3 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold 
                                    <?= strpos($item['quality_grade'], 'A') !== false ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-400 border border-rose-500/30' ?>">
                                    <?= htmlspecialchars($item['quality_grade']) ?>
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] <?= $item['status'] === 'Approved' ? 'bg-blue-500/10 text-blue-400' : 'bg-amber-500/10 text-amber-400' ?>">
                                    <?= htmlspecialchars($item['status']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- DEVELOPER FOOTER -->
        <footer class="glass-card rounded-2xl p-4 text-center text-xs text-slate-400 flex flex-col md:flex-row justify-between items-center gap-2">
            <span> Business Idea Creation Project • PALM AI</span>
            <span>ผู้จัดทำ: รหัสนิสิต <strong class="text-slate-200">67160214</strong></span>
        </footer>

    </main>

    <script>
        lucide.createIcons();

        // 1. Ripeness Doughnut Chart
        new Chart(document.getElementById('ripenessChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['ปาล์มสุก', 'ปาล์มดิบ', 'สุกเกิน/ร่วง'],
                datasets: [{
                    data: [
                        <?= number_format($quality['avg_ripe'] ?? 80, 1) ?>, 
                        <?= number_format($quality['avg_unripe'] ?? 12, 1) ?>, 
                        <?= number_format($quality['avg_overripe'] ?? 8, 1) ?>
                    ],
                    backgroundColor: ['#10b981', '#f43f5e', '#f59e0b'],
                    borderWidth: 0
                }]
            },
            options: {
                plugins: { legend: { labels: { color: '#94a3b8', font: { family: 'Prompt' } } } },
                cutout: '70%'
            }
        });

        // 2. Supplier Bar Chart
        new Chart(document.getElementById('supplierChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($suppliers, 'supplier_name')) ?>,
                datasets: [
                    {
                        label: 'Estimated OER (%)',
                        data: <?= json_encode(array_column($suppliers, 'avg_oer')) ?>,
                        backgroundColor: '#10b981',
                        borderRadius: 6
                    },
                    {
                        label: 'ปาล์มดิบ (%)',
                        data: <?= json_encode(array_column($suppliers, 'avg_unripe')) ?>,
                        backgroundColor: '#f43f5e',
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { labels: { color: '#94a3b8', font: { family: 'Prompt' } } } },
                scales: {
                    x: { ticks: { color: '#94a3b8', font: { family: 'Prompt' } }, grid: { display: false } },
                    y: { ticks: { color: '#94a3b8' }, grid: { color: '#334155' } }
                }
            }
        });
    </script>
</body>
</html>