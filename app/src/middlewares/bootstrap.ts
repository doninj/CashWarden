import type { NavigationGuardNext, RouteLocationNormalized, Router } from "vue-router"

export interface MiddlewareContext {
  from: RouteLocationNormalized,
  to: RouteLocationNormalized,
  next: NavigationGuardNext,
  router: Router,
}

export type Middleware = (context: MiddlewareContext) => Middleware

// Creates a `nextMiddleware()` function which not only
// runs the default `next()` callback but also triggers
// the subsequent Middleware function.
export function nextFactory(context: MiddlewareContext, middlewares: Middleware[], index: number) {
  const subsequentMiddleware = middlewares[index]
  // If no subsequent Middleware exists,
  // the default `next()` callback is returned.
  if (!subsequentMiddleware) return context.next

  return (...parameters: any) => {
    // Run the default Vue Router `next()` callback first.
    // @ts-ignore
    context.next(...parameters)
    // Then run the subsequent Middleware with a new
    // `nextMiddleware()` callback.
    const nextMiddleware = nextFactory(context, middlewares, index + 1)
    subsequentMiddleware({ ...context, next: nextMiddleware })
  }
}
