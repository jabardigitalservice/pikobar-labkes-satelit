import Vue from 'vue'
import { library, config } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import {
  faUser, faLock, faSignOutAlt, faCog, faCaretUp, faCaretDown,
} from '@fortawesome/free-solid-svg-icons'

import {
  faGithub, faGoogle
} from '@fortawesome/free-brands-svg-icons'

config.autoAddCss = false

library.add(
  faUser, faLock, faSignOutAlt, faCog, faCaretUp, faCaretDown,
  faGithub, faGoogle
)

Vue.component('fa', FontAwesomeIcon)
