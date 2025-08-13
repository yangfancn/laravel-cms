export interface FormFieldProps {
  [key: string]: any
}

export interface FormProps {
  fields: FormFieldProps[]
  action: string
  method: "PUT" | "POST"
  data: any
  errors?: {
    [key: string]: any
  }
  precognitive: boolean
}
