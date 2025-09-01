export type FormFieldProps = Record<string, any>;

export interface FormProps {
  fields: FormFieldProps[]
  action: string
  method: "PUT" | "POST"
  data: any
  errors?: Record<string, any>
  precognitive: boolean
}
