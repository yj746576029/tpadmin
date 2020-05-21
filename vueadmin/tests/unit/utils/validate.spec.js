import { validUserName, isExternal } from '@/utils/validate.js'

describe('Utils:validate', () => {
  it('validUserName', () => {
    expect(validUserName('admin')).toBe(true)
    expect(validUserName('editor')).toBe(true)
    expect(validUserName('xxxx')).toBe(false)
  })
  it('isExternal', () => {
    expect(isExternal('https://github.com/PanJiaChen/vue-element-admin')).toBe(true)
    expect(isExternal('http://github.com/PanJiaChen/vue-element-admin')).toBe(true)
    expect(isExternal('github.com/PanJiaChen/vue-element-admin')).toBe(false)
    expect(isExternal('/dashboard')).toBe(false)
    expect(isExternal('./dashboard')).toBe(false)
    expect(isExternal('dashboard')).toBe(false)
  })
})
