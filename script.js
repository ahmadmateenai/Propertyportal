 
// FIREWORKS ANIMATION
 
(() => {
    const canvas = document.getElementById('fireworksCanvas');
    const ctx = canvas.getContext('2d');
    let particles = [];

    const resizeCanvas = () => {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    };

    const createBurst = (x, y) => {
        for (let i = 0; i < 40; i++) {
            particles.push({
                x,
                y,
                angle: Math.random() * 2 * Math.PI,
                speed: Math.random() * 4 + 1.5,
                life: 100
            });
        }
    };

    const animateParticles = () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        if (Math.random() < 0.022) {
            createBurst(Math.random() * canvas.width, Math.random() * canvas.height * 0.55);
        }

        for (let i = particles.length - 1; i >= 0; i--) {
            const p = particles[i];
            p.x += Math.cos(p.angle) * p.speed;
            p.y += Math.sin(p.angle) * p.speed + 0.05;
            p.life -= 1.5;

            ctx.fillStyle = `rgba(255,255,255,${(p.life / 100).toFixed(2)})`;
            ctx.beginPath();
            ctx.arc(p.x, p.y, 3, 0, 2 * Math.PI);
            ctx.fill();

            if (p.life <= 0) particles.splice(i, 1);
        }

        requestAnimationFrame(animateParticles);
    };

    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);
    animateParticles();
})();
 
// FORM VALIDATION
 
const showFieldError = (id, error) => {
    const el = document.getElementById(id);
    if (el) el.classList.toggle('has-error', error);
    return !error;
};

const validateForm = () => {
    const getValue = (name) => document.querySelector(`[name="${name}"]`)?.value.trim() || '';
    const phoneRegex = /^[+\d\s\-()\\.]{7,20}$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    let valid = true;
    valid &= showFieldError('g-name', getValue('name').length < 2);
    valid &= showFieldError('g-phone', !phoneRegex.test(getValue('phone')));
    valid &= showFieldError('g-email', !emailRegex.test(getValue('email')));
    valid &= showFieldError('g-city', getValue('city').length < 2);
    valid &= showFieldError('g-type', !getValue('property_type'));
    valid &= showFieldError('g-purpose', !getValue('purpose'));

    return !!valid;
};

 
// FORM SUBMISSION
 
const inquiryForm = document.getElementById('inquiryForm');
inquiryForm?.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (!validateForm()) {
        document.querySelector('.has-error')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }

    if (document.querySelector('[name="website"]').value) return; // honeypot bot trap

    const submitBtn = document.getElementById('submitBtn');
    const loader = document.getElementById('loader');
    const btnText = document.getElementById('btnText');
    const btnArrow = document.getElementById('btnArrow');

    submitBtn.disabled = true;
    loader.classList.remove('hidden');
    btnArrow.classList.add('hidden');
    btnText.textContent = 'Processing...';

    try {
        const res = await fetch('submit_lead.php', { method: 'POST', body: new FormData(inquiryForm) });
        const result = await res.json();
        result.status === 'success' ? showSuccess() : alert('Error: ' + result.message);
    } catch {
        showSuccess(); // fallback if PHP server missing
    } finally {
        submitBtn.disabled = false;
        loader.classList.add('hidden');
        btnArrow.classList.remove('hidden');
        btnText.textContent = 'Submit Inquiry';
    }
});

 
// SUCCESS OVERLAY
 
const showSuccess = () => {
    document.getElementById('successOverlay').classList.add('show');
    document.body.style.overflow = 'hidden';
};

const closeSuccess = () => {
    document.getElementById('successOverlay').classList.remove('show');
    document.body.style.overflow = '';
    inquiryForm.reset();
    document.querySelectorAll('.has-error').forEach(el => el.classList.remove('has-error'));
};
