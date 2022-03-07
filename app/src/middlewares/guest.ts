import type { MiddlewareContext } from "./bootstrap"
import { useAuth } from "@/stores/auth"
import { Routes } from "@/router"

export default async function guest({ next }: MiddlewareContext) {
  const auth = useAuth()
  await auth.recoverSession()

  if(auth.isLoggedIn && auth.hasBankLinked) {
    return next({ path: Routes.Home })
  }

  return next();
}
