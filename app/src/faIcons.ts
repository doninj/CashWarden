import {library} from '@fortawesome/fontawesome-svg-core'
import {
    faPieChart, faHandHoldingDollar,
    faMoneyCheck, faCog,
    faSignOutAlt, faCircleCheck, faEllipsisV
} from "@fortawesome/free-solid-svg-icons"


/** Icones de fontawesome utilisé avec le composant Icon dans components/Icon  */

/**  ajoute le nom de l'icone
 *   @example
 *   composant faPieCHart -> pie-chart
 * */
export type IconName = "pie-chart" | "hand-holding-dollar" | "money-check" | "cog" | "sign-out-alt"| "circle-check" | "ellipsis-v"

/** ajoute le composant de l'icone */
const faIcons = [
    faCircleCheck,
    faPieChart,
    faHandHoldingDollar,
    faMoneyCheck,
    faCog,
    faEllipsisV,
    faSignOutAlt
]

faIcons.forEach(icon => library.add(icon))
