import type { MiddlewareContext } from "./bootstrap"
import { useAuth } from "@/stores/auth"
import { Routes } from "@/router"

export default async function auth({ next, to, from }: MiddlewareContext) {
  const auth = useAuth()
  await auth.recoverSession()

  if(!auth.isLoggedIn) {
    return next(Routes.Login)
  }

  if(to.name !== Routes.BankRegister && !auth.hasBankLinked) {
      return next(Routes.BankRegister)
  }

  else if (auth.hasBankLinked && to.name === Routes.BankRegister) {
    return next(Routes.Home)
  }

  return next();
}
