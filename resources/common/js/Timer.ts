export interface TimerOptions {
  delay: number
  onChange?: (remaining: number) => void
  onFinish?: () => void
}

export default class Timer {
  private timerId: number | null = null
  private startTime: number | null = null
  private stop: number
  private remaining: number
  private running = false
  private counter: number
  private readonly onFinish?: () => void
  private readonly onChange?: (remaining: number) => void

  constructor(options: TimerOptions) {
    this.onChange = options.onChange
    this.onFinish = options.onFinish
    this.remaining = this.stop = options.delay
    this.counter = 0
    this.start()
  }

  private step = (timestamp: number) => {
    this.startTime ??= timestamp;
    const elapsed = timestamp - this.startTime
    this.remaining = Math.max(0, this.stop - elapsed)
    this.counter++

    if (this.counter % 6 === 0 && this.onChange) {
      this.onChange(this.remaining)
    }

    if (this.remaining > 0) {
      this.timerId = requestAnimationFrame(this.step)
    } else {
      this.pause()
      if (this.onFinish) {
        this.onFinish()
      }
    }
  }

  private start() {
    if (!this.running && this.remaining > 0) {
      this.running = true
      this.startTime = null
      this.timerId = requestAnimationFrame(this.step)
    }
  }

  public pause() {
    if (this.timerId !== null) {
      cancelAnimationFrame(this.timerId)
      this.timerId = null
      this.running = false
      // Adjust stop to reflect the time remaining
      if (this.startTime !== null) {
        const elapsed = performance.now() - this.startTime
        this.stop -= elapsed
      }
    }
  }

  public resume() {
    if (!this.running && this.remaining > 0) {
      this.running = true
      this.startTime = null // Reset start time for accurate calculation
      this.timerId = requestAnimationFrame(this.step)
    }
  }
}
