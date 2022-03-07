export default function genAvatarUrl(firstName: string, lastName: string): string {
  const uiParams = {
    rounded: true,
    bold: true,
    name: `${firstName}+${lastName}`,
    background: "6366F1",
    color: "fff"
  }

  const userAvatarUrl = `https://ui-avatars.com/api/`


  // @ts-ignore: Object.entries is not defined ???
  const params = "?" + Object.entries(uiParams).map(([key, value]) => `${key}=${value}`).join("&")
  console.log(params)

  return userAvatarUrl + params
}
