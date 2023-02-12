import moment from 'moment'

import 'moment/locale/ru'

moment.locale('ru')

export default (ctx, inject) => {
  ctx.$moment = moment
  inject('moment', moment)
}
