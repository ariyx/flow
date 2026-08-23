const messages = {
  'app.title': 'Webilo Flow',
  'auth.login': 'Sign in',
  'auth.register': 'Create account',
  'auth.forgot': 'Forgot password?',
  'auth.reset': 'Reset password',
  'auth.name': 'Name',
  'auth.email': 'Email address',
  'auth.password': 'Password',
  'auth.confirmPassword': 'Confirm password',
  'auth.noAccount': 'Need an account?',
  'auth.haveAccount': 'Already have an account?',
  'auth.requestReset': 'Send reset link',
  'auth.resetRequested': 'If that address belongs to an account, a reset link has been sent.',
  'auth.passwordReset': 'Your password has been reset. You can sign in now.',
  'auth.loading': 'Loading…',
  'auth.error': 'Something went wrong. Please try again.',
  'auth.required': 'Please complete all fields.',
  'workspace.title': 'Your workspace',
  'workspace.signedInAs': 'Signed in as',
  'workspace.identifier': 'Workspace ID',
  'workspace.placeholder': 'Your Webilo Flow workspace is ready.',
  'auth.logout': 'Sign out',
} as const

export type MessageKey = keyof typeof messages

export const t = (key: MessageKey): string => messages[key]
