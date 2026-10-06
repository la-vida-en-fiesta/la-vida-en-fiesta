(() => {
  'use strict';
  const body = document.body;
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)');
  const read = key => { try { return sessionStorage.getItem(key); } catch (_) { return null; } };
  const write = (key, value) => { try { sessionStorage.setItem(key, value); } catch (_) {} };
  const toggle = document.querySelector('.effects-toggle');
  const storedEffects = read('fiesta-effects-paused');
  let paused = storedEffects === 'yes' || (storedEffects !== 'no' && reduced.matches);
  let motionOptIn = storedEffects === 'no';
  const witch = document.querySelector('.flying-witch');
  let flight = null, flightTimer = null;
  const random = (min, max) => min + Math.random() * (max - min);
  const fly = () => {
    if (!witch || paused || document.hidden) return;
    const reverse = Math.random() < .5;
    const width = window.innerWidth, height = window.innerHeight;
    const size = witch.getBoundingClientRect().width;
    const start = reverse ? width + size : -size * 2;
    const end = reverse ? -size * 2 : width + size;
    const duration = random(7000, 13000);
    const baseline = random(height * .18, height * .68);
    const amplitude = random(20, Math.min(80, height * .1));
    const waves = random(1.5, 3.5), phase = random(0, Math.PI * 2);
    witch.dataset.direction = reverse ? 'left' : 'right';
    witch.style.setProperty('--witch-direction', reverse ? '-1' : '1');
    const frames = Array.from({length: 61}, (_, index) => {
      const t = index / 60;
      const y = baseline + Math.sin(t * Math.PI * 2 * waves + phase) * amplitude;
      return {transform: `translate3d(${start + (end - start) * t}px,${y}px,0)`, opacity: index === 0 || index === 60 ? 0 : .55, offset: t};
    });
    flight = witch.animate(frames, {duration, easing: 'linear'});
    flight.onfinish = () => { flight = null; flightTimer = setTimeout(fly, random(2500, 9000)); };
  };
  const syncFlight = () => {
    clearTimeout(flightTimer); flightTimer = null;
    if (paused) { flight?.cancel(); flight = null; }
    else if (document.hidden) flight?.pause();
    else if (flight) flight.play();
    else flightTimer = setTimeout(fly, random(500, 2000));
  };
  const garland = document.querySelector('.pumpkin-garland');
  if (garland) {
    const fitGarland = () => {
      const count = Math.max(1, Math.round(garland.clientWidth / (window.innerWidth <= 700 ? 110 : 140)));
      if (garland.childElementCount === count) return;
      garland.replaceChildren(...Array.from({length:count}, () => document.createElement('span')));
    };
    fitGarland();
    new ResizeObserver(fitGarland).observe(garland);
  }
  const syncEffects = () => {
    body.classList.toggle('effects-paused', paused);
    body.classList.toggle('motion-opt-in', motionOptIn && !paused);
    if (toggle) { toggle.textContent = paused ? 'Activar efectos' : 'Pausar efectos'; toggle.setAttribute('aria-pressed', String(paused)); }
    syncFlight();
  };
  if (toggle) {
    toggle.hidden = false;
    toggle.addEventListener('click', () => { paused = !paused; motionOptIn = !paused; write('fiesta-effects-paused', paused ? 'yes' : 'no'); syncEffects(); });
  }
  syncEffects();
  document.addEventListener('visibilitychange', () => { body.classList.toggle('tab-hidden', document.hidden); syncFlight(); });
  reduced.addEventListener('change', event => { if (event.matches) { paused = true; motionOptIn = false; syncEffects(); } });
  const intro = document.querySelector('#halloween-intro');
  if (!intro || typeof intro.showModal !== 'function') return;
  const pumpkin = intro.querySelector('.intro-pumpkin');
  let timers = [], audio = null, previousFocus = null, playing = false;
  const later = (fn, delay) => timers.push(window.setTimeout(fn, delay));
  const finish = () => {
    timers.forEach(clearTimeout); timers = []; playing = false;
    if (audio) { audio.close().catch(() => {}); audio = null; }
    intro.close(); body.classList.remove('intro-open'); intro.classList.remove('is-laughing', 'is-zooming', 'is-revealing');
    intro.querySelectorAll('.intro-enter').forEach(button => { button.disabled = false; });
    intro.dataset.phase = 'closed'; write('fiesta-halloween-intro', 'seen');
    if (previousFocus && previousFocus !== body) previousFocus.focus();
    else document.querySelector('.entry-nav a')?.focus();
  };
  // Local procedural cackle: no external audio requests or autoplay.
  const cackle = () => {
    const Audio = window.AudioContext || window.webkitAudioContext;
    if (!Audio) { intro.dataset.sound = 'unavailable'; return; }
    try {
      audio = new Audio();
      audio.resume().then(() => { intro.dataset.sound = audio?.state === 'running' ? 'playing' : 'blocked'; });
      const master = audio.createGain(); master.gain.value = .12; master.connect(audio.destination);
      for (let i = 0; i < 6; i++) {
        const at = audio.currentTime + .035 + i * .145;
        const voice = audio.createOscillator(), vowel = audio.createBiquadFilter(), envelope = audio.createGain();
        voice.type = 'sawtooth'; voice.frequency.setValueAtTime(310 - i * 13, at); voice.frequency.exponentialRampToValueAtTime(160 - i * 7, at + .105);
        vowel.type = 'bandpass'; vowel.frequency.value = 1250; vowel.Q.value = 1.2;
        envelope.gain.setValueAtTime(0, at); envelope.gain.linearRampToValueAtTime(.7, at + .025); envelope.gain.exponentialRampToValueAtTime(.001, at + .12);
        voice.connect(vowel); vowel.connect(envelope); envelope.connect(master); voice.start(at); voice.stop(at + .13);
      }
    } catch (_) { intro.dataset.sound = 'unavailable'; }
  };
  const open = () => {
    if (!pumpkin.complete || !pumpkin.naturalWidth || intro.open) return;
    previousFocus = document.activeElement;
    intro.dataset.phase = 'ready'; intro.dataset.sound = 'off'; intro.showModal(); body.classList.add('intro-open');
  };
  intro.querySelector('.intro-skip').addEventListener('click', finish);
  intro.addEventListener('cancel', event => { event.preventDefault(); finish(); });
  intro.querySelectorAll('.intro-enter').forEach(button => button.addEventListener('click', () => {
    if (playing) return;
    playing = true; write('fiesta-halloween-intro', 'seen');
    intro.querySelectorAll('.intro-enter').forEach(item => { item.disabled = true; });
    if (button.dataset.sound === 'yes') cackle();
    if ((reduced.matches && !motionOptIn) || paused) { if (button.dataset.sound === 'yes') later(finish, 950); else finish(); return; }
    intro.dataset.phase = 'laughing'; intro.classList.add('is-laughing');
    later(() => { intro.classList.remove('is-laughing'); intro.classList.add('is-zooming'); intro.dataset.phase = 'zooming'; }, 1000);
    later(() => { intro.classList.add('is-revealing'); intro.dataset.phase = 'revealing'; }, 2100);
    later(finish, 2570);
  }));
  const replay = document.createElement('button'); replay.type = 'button'; replay.className = 'replay-intro'; replay.textContent = 'Ver entrada de Halloween';
  replay.disabled = !pumpkin.complete || !pumpkin.naturalWidth;
  pumpkin.addEventListener('load', () => { replay.disabled = false; }, {once:true});
  pumpkin.addEventListener('error', () => { replay.hidden = true; }, {once:true});
  replay.addEventListener('click', open); document.querySelector('.site-footer > div')?.append(replay);
  if (!read('fiesta-halloween-intro') && !reduced.matches && !paused && !location.hash) {
    if (pumpkin.complete) open(); else pumpkin.addEventListener('load', open, {once:true});
  }
})();
