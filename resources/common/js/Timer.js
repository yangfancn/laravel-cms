export default class Timer {
    timerId = null;
    startTime = null;
    stop;
    remaining;
    running = false;
    counter;
    onFinish;
    onChange;
    constructor(options) {
        this.onChange = options.onChange;
        this.onFinish = options.onFinish;
        this.remaining = this.stop = options.delay;
        this.counter = 0;
        this.start();
    }
    step = (timestamp) => {
        if (!this.startTime)
            this.startTime = timestamp;
        const elapsed = timestamp - this.startTime;
        this.remaining = Math.max(0, this.stop - elapsed);
        this.counter++;
        if (this.counter % 6 === 0 && this.onChange) {
            this.onChange(this.remaining);
        }
        if (this.remaining > 0) {
            this.timerId = requestAnimationFrame(this.step);
        }
        else {
            this.pause();
            if (this.onFinish) {
                this.onFinish();
            }
        }
    };
    start() {
        if (!this.running && this.remaining > 0) {
            this.running = true;
            this.startTime = null;
            this.timerId = requestAnimationFrame(this.step);
        }
    }
    pause() {
        if (this.timerId !== null) {
            cancelAnimationFrame(this.timerId);
            this.timerId = null;
            this.running = false;
            // Adjust stop to reflect the time remaining
            if (this.startTime !== null) {
                const elapsed = performance.now() - this.startTime;
                this.stop -= elapsed;
            }
        }
    }
    resume() {
        if (!this.running && this.remaining > 0) {
            this.running = true;
            this.startTime = null; // Reset start time for accurate calculation
            this.timerId = requestAnimationFrame(this.step);
        }
    }
}
