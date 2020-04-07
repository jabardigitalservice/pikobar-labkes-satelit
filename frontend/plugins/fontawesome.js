import Vue from 'vue'
import { library, config } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import {
  faUser, faLock, faSignOutAlt, faCog
} from '@fortawesome/free-solid-svg-icons'

import {
  faGithub, faGoogle
} from '@fortawesome/free-brands-svg-icons'

config.autoAddCss = false

library.add(
  faUser, faLock, faSignOutAlt, faCog, faGithub, faGoogle
)

Vue.component('fa', FontAwesomeIcon)
