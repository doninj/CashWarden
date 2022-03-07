import type { MiddlewareContext } from "./bootstrap"
import { useAuth } from "@/stores/auth"
import { Routes } from "@/router"

export default async function auth({ next, to, from }: MiddlewareContext) {
  const auth = useAuth()
  await auth.recoverSession()

  console.log("auth", auth)
  if(!auth.isLoggedIn) {
    // return next(Routes.Login)
  }

  if(!auth.hasBankLinked) {
    // return next(Routes.BankRegister)
  }

  return next();
}
