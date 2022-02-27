import type { MiddlewareContext } from "./bootstrap"
import { useAuth } from "@/stores/auth"
import { Routes } from "@/router"

export default function auth({ next, to }: MiddlewareContext) {
  const auth = useAuth()

  if(!auth.isLoggedIn) {
    return next(Routes.Login)
  }

  return next();
}
