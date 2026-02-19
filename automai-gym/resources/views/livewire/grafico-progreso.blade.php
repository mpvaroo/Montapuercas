<div>
    {{-- ══════════════════════════════════════
         CABECERA: título + selector de métrica
    ══════════════════════════════════════ --}}
    <div class="gc-header">
        <div class="gc-title-row">
            <span class="prog-badge">
                <svg viewBox="0 0 24 24">
                    <path d="M4 19h16v2H2V3h2v16Zm4-2H6V10h2v7Zm5 0h-2V6h2v11Zm5 0h-2V12h2v5Z" />
                </svg>
            </span>
            <span class="gc-title">Evolución de <strong>{{ $metricLabel }}</strong> — últimas 10 semanas</span>
        </div>

        <div class="gc-tabs" role="group">
            @foreach ($metrics as $key => $info)
                <button class="gc-tab {{ $currentMetric === $key ? 'gc-tab--active' : '' }}"
                    wire:click="selectMetric('{{ $key }}')" type="button">{{ $info['label'] }}</button>
            @endforeach
        </div>
    </div>

    {{-- ══════════════════════════════════════
         GRÁFICO
    ══════════════════════════════════════ --}}
    <div class="chart-wrap">
        <div class="chart-grid">
            <div class="y-axis" id="gcYAxis">
                <span>—</span><span>—</span><span>—</span><span>—</span>
            </div>
            <div class="grid-lines"><span></span><span></span><span></span></div>
            <svg class="chart-svg" viewBox="0 0 700 260" preserveAspectRatio="none" aria-hidden="true">
                <defs>
                    <linearGradient id="gcFillGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="rgba(190,145,85,0.9)" stop-opacity="0.45" id="gcGradStop0" />
                        <stop offset="100%" stop-color="rgba(190,145,85,0.9)" stop-opacity="0" id="gcGradStop1" />
                    </linearGradient>
                    <filter id="gcGlow" x="-20%" y="-20%" width="140%" height="140%">
                        <feGaussianBlur stdDeviation="3" result="blur" />
                        <feMerge>
                            <feMergeNode in="blur" />
                            <feMergeNode in="SourceGraphic" />
                        </feMerge>
                    </filter>
                </defs>
                <path id="gcAreaPath" d="" fill="url(#gcFillGrad)" opacity="0.35"></path>
                <path id="gcLineCream" d="" fill="none" stroke="rgba(239,231,214,0.40)" stroke-width="1.5"
                    opacity="0.6"></path>
                <path id="gcLineColorEl" d="" fill="none" stroke="rgba(190,145,85,0.90)" stroke-width="2.5"
                    filter="url(#gcGlow)"></path>
                <g id="gcDots" fill="rgba(239,231,214,0.95)" stroke="rgba(0,0,0,0.8)" stroke-width="2"></g>
            </svg>
            <div class="x-axis" id="gcXAxis"></div>
        </div>

        {{-- Mensaje sin datos --}}
        <div id="gcEmpty"
            style="display:none; text-align:center; padding:28px 0; color:rgba(239,231,214,0.4); font-size:13px; font-family:var(--sans);">
            Sin registros de <strong>{{ $metricLabel }}</strong> para mostrar
        </div>
    </div>


</div>

@script
    <script>
        (function() {
            // ─── Función de dibujado ────────────────────────────────────
            function draw(d) {
                if (!d) return;
                const labels = d.labels || [];
                const values = d.values || [];
                const color = d.color || 'rgba(190,145,85,0.9)';
                const unit = d.unit || '';

                const el = $wire.$el;
                const emptyEl = el.querySelector('#gcEmpty');
                const grid = el.querySelector('.chart-grid');
                const yAxis = el.querySelector('#gcYAxis');
                const xAxis = el.querySelector('#gcXAxis');
                const areaPath = el.querySelector('#gcAreaPath');
                const lcream = el.querySelector('#gcLineCream');
                const lcolor = el.querySelector('#gcLineColorEl');
                const dotsG = el.querySelector('#gcDots');
                const stop0 = el.querySelector('#gcGradStop0');
                const stop1 = el.querySelector('#gcGradStop1');

                const valid = values.filter(v => v !== null && v !== undefined && isFinite(Number(v)));

                // ── Sin datos ──────────────────────────────────
                if (!valid.length) {
                    if (emptyEl) emptyEl.style.display = 'block';
                    if (grid) grid.style.visibility = 'hidden';
                    if (yAxis) yAxis.innerHTML = '<span>—</span><span>—</span><span>—</span><span>—</span>';
                    return;
                }
                if (emptyEl) emptyEl.style.display = 'none';
                if (grid) grid.style.visibility = 'visible';

                // ── Actualizar color dinámico ──────────────────
                if (stop0) stop0.setAttribute('stop-color', color);
                if (stop1) stop1.setAttribute('stop-color', color);
                if (lcolor) lcolor.setAttribute('stroke', color);

                // ── Escala ────────────────────────────────────
                const nums = valid.map(Number);
                const W = 700,
                    H = 260;
                const rawMin = Math.min(...nums),
                    rawMax = Math.max(...nums);
                const expand = rawMin === rawMax ? Math.max(rawMin * 0.05, 1) : 0;
                const minV = rawMin * 0.97 - expand;
                const maxV = rawMax * 1.03 + expand;
                const rng = maxV - minV || 1;

                const n = labels.length;
                const toX = i => n <= 1 ? W / 2 : (i / (n - 1)) * W;
                const toY = v => H - ((Number(v) - minV) / rng) * H;

                // ── SVG paths ─────────────────────────────────
                let line = '',
                    area = '',
                    firstI = -1,
                    lastI = -1;
                for (let i = 0; i < n; i++) {
                    const v = values[i];
                    if (v === null || v === undefined || !isFinite(Number(v))) continue;
                    const x = toX(i),
                        y = toY(v);
                    if (firstI === -1) {
                        line = `M ${x} ${y}`;
                        area = `M ${x} ${H} L ${x} ${y}`;
                        firstI = i;
                    } else {
                        line += ` L ${x} ${y}`;
                        area += ` L ${x} ${y}`;
                    }
                    lastI = i;
                }
                if (firstI === -1) return;
                area += ` L ${toX(lastI)} ${H} Z`;

                if (areaPath) areaPath.setAttribute('d', area);
                if (lcream) lcream.setAttribute('d', line);
                if (lcolor) lcolor.setAttribute('d', line);

                // ── Puntos ────────────────────────────────────
                if (dotsG) {
                    dotsG.innerHTML = '';
                    for (let i = 0; i < n; i++) {
                        const v = values[i];
                        if (v === null || v === undefined || !isFinite(Number(v))) continue;
                        const c = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                        c.setAttribute('cx', toX(i));
                        c.setAttribute('cy', toY(v));
                        c.setAttribute('r', '5');
                        const t = document.createElementNS('http://www.w3.org/2000/svg', 'title');
                        t.textContent = `${Number(v).toFixed(2)} ${unit}`;
                        c.appendChild(t);
                        dotsG.appendChild(c);
                    }
                }

                // ── Eje X ─────────────────────────────────────
                if (xAxis) {
                    xAxis.style.display = 'grid';
                    xAxis.style.gridTemplateColumns = labels.map(() => '1fr').join(' ');
                    xAxis.innerHTML = labels.map(l => `<span>${l}</span>`).join('');
                }

                // ── Eje Y ─────────────────────────────────────
                if (yAxis) {
                    yAxis.innerHTML = '';
                    for (let i = 0; i < 4; i++) {
                        const val = maxV - (rng / 3) * i;
                        const s = document.createElement('span');
                        s.textContent = Number(val).toFixed(1);
                        yAxis.appendChild(s);
                    }
                }
            }

            // ─── 1) Dibujo inicial con datos PHP (sin timing issues) ────
            // ─── 1) Dibujo inicial con datos PHP (sin timing issues) ────
            draw(@js($chartData));

            // ─── 2) Redibujar cuando Livewire cambia la métrica ─────────
            $wire.$watch('chartData', data => draw(data));
        })();
    </script>
@endscript
