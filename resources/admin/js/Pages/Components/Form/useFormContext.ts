import type { InjectionKey } from "vue"
import { inject, provide } from "vue"

export type FormErrors = Record<string, string | null>

export interface FormContext {
  registerField: (name: string, focusOnError: () => void) => void
  unregisterField: (name: string) => void
  getError: (name: string, strict?: boolean) => string | null
  clearError: (name: string) => void
  sortError: (prefixName: string, newIndex: number, oldIndex: number) => void
  addAllowSubmitHandler: (fn: () => boolean) => () => void
}

const FormContextKey: InjectionKey<FormContext> = Symbol("FormContext")

export function provideFormContext(ctx: FormContext) {
  provide(FormContextKey, ctx)
}

export function useFormContext(): FormContext {
  const ctx = inject(FormContextKey)
  if (!ctx) throw new Error("FormContext is not provided")
  return ctx
}
