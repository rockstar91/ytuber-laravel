export { default as AccountChooser } from '../..\\components\\AccountChooser.vue'
export { default as CompleteForPublic } from '../..\\components\\CompleteForPublic.vue'
export { default as DalyBonus } from '../..\\components\\DalyBonus.vue'
export { default as Dashboard } from '../..\\components\\Dashboard.vue'
export { default as EllipsisLoader } from '../..\\components\\EllipsisLoader.vue'
export { default as FloatNav } from '../..\\components\\FloatNav.vue'
export { default as Footer } from '../..\\components\\Footer.vue'
export { default as GuestHeader } from '../..\\components\\GuestHeader.vue'
export { default as Header } from '../..\\components\\Header.vue'
export { default as LoadingBar } from '../..\\components\\LoadingBar.vue'
export { default as ModerateAccount } from '../..\\components\\ModerateAccount.vue'
export { default as NavBar } from '../..\\components\\NavBar.vue'
export { default as NewsModule } from '../..\\components\\NewsModule.vue'
export { default as NotificationsModule } from '../..\\components\\NotificationsModule.vue'
export { default as Sidebar } from '../..\\components\\Sidebar.vue'
export { default as SidebarSecondary } from '../..\\components\\SidebarSecondary.vue'
export { default as SupportForm } from '../..\\components\\SupportForm.vue'
export { default as TaskDeleteModal } from '../..\\components\\TaskDeleteModal.vue'
export { default as TaskDetailCompletions } from '../..\\components\\TaskDetailCompletions.vue'
export { default as TaskFilter } from '../..\\components\\TaskFilter.vue'
export { default as TaskForm } from '../..\\components\\TaskForm.vue'
export { default as TopChannel } from '../..\\components\\TopChannel.vue'
export { default as UserActivity } from '../..\\components\\UserActivity.vue'
export { default as UserReferrals } from '../..\\components\\UserReferrals.vue'
export { default as YoutubePlayer } from '../..\\components\\YoutubePlayer.vue'
export { default as YtPlayer } from '../..\\components\\YtPlayer.vue'
export { default as YtuberChat } from '../..\\components\\YtuberChat.vue'

// nuxt/nuxt.js#8607
function wrapFunctional(options) {
  if (!options || !options.functional) {
    return options
  }

  const propKeys = Array.isArray(options.props) ? options.props : Object.keys(options.props || {})

  return {
    render(h) {
      const attrs = {}
      const props = {}

      for (const key in this.$attrs) {
        if (propKeys.includes(key)) {
          props[key] = this.$attrs[key]
        } else {
          attrs[key] = this.$attrs[key]
        }
      }

      return h(options, {
        on: this.$listeners,
        attrs,
        props,
        scopedSlots: this.$scopedSlots,
      }, this.$slots.default)
    }
  }
}
