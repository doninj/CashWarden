import type { MiddlewareContext } from "./bootstrap"
import { useAuth } from "@/stores/auth"
import { Routes } from "@/router"

export default async function guest({ next }: MiddlewareContext) {
  const auth = useAuth()
  await auth.recoverSession()

  console.log("guest", auth.user)

  // if(auth.isLoggedIn && auth.hasBankLinked) {
  //   return next({ name: Routes.Home })
  // }
  // else if (auth.isLoggedIn && !auth.hasBankLinked) {
  //   return next({ name: Routes.BankRegister })
  // }

  return next();
}
