export default function errorsToString(errors: ParseError[]) {
  const fieldTraductions = {
    email: "Email",
    firstName: "Prénom",
    lastName: "Nom",
    password: "Mot de passe",
    passwordConfirmation: "Confirmation du mot de passe",
  }

 return Object.entries(errors).reduce(
   // @ts-expect-error: since entries is not found it cannot resolve the type of the values returned
   (acc, [field, message]) => `${acc}\n ${fieldTraductions[field]}: ${message}`,
   ""
 )
}
