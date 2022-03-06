import type { MiddlewareContext } from "./bootstrap"
import { useAuth } from "@/stores/auth"
import { Routes } from "@/router"

export default async function auth({ next, to, from }: MiddlewareContext) {
  const auth = useAuth()

  if(!auth.wasRecoveryTried) {
    console.log("revovery not tried")
    await auth.recoverToken()
    await auth.recoverUser()
  }

  console.log("auth", auth)
  if(!auth.isLoggedIn) {
    // return next(Routes.Login)
  }

  if(!auth.hasBankLinked) {
    // return next(Routes.BankRegister)
  }

  return next();
}
