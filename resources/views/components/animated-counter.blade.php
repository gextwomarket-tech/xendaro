@props(['value', 'suffix' => '', 'prefix' => '', 'duration' => 1500])
<span
    x-data="{ display: 0 }"
    x-init="
        const target = {{ (float) $value }};
        const el = $el;
        const io = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                const start = performance.now();
                const step = (now) => {
                    const p = Math.min((now - start) / {{ (int) $duration }}, 1);
                    display = Math.floor(p * target);
                    if (p < 1) requestAnimationFrame(step); else display = target;
                };
                requestAnimationFrame(step);
                io.unobserve(el);
            });
        }, { threshold: 0.3 });
        io.observe(el);
    "
    x-text="'{{ $prefix }}' + display.toLocaleString('fr-FR') + '{{ $suffix }}'"
    {{ $attributes }}
>{{ $prefix }}0{{ $suffix }}</span>
