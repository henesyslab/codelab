module.exports = {
  /**
   * Gera uma namespace com base na string passada por parâmetro.
   * @param  string  str
   */
  generateNamespace(str) {
    if (str === '') {
      return str
    }

    const ucwords = require('locutus/php/strings/ucwords')
    const explode = require('locutus/php/strings/explode')
    const substr = require('locutus/php/strings/substr')
    const join = require('locutus/php/strings/join')

    slug = getSlug(str, {
      maintainCase: true,
      separator: ' ',
    })

    slug = ucwords(slug)
    slug = explode(' ', slug)

    if (slug.length === 1) {
      slug = substr(slug, 0, 12)
    } else if (slug.length > 1 && slug.length < 5) {
      slug = collect(slug).map(item => {
        return substr(item, 0, (12 / slug.length))
      }).all()
    } else {
      slug = collect(slug).map(item => {
        return substr(item, 0, 2)
      }).all()
    }

    slug = join('', slug)
    slug = substr(slug, 0, 12)

    return slug
  }
}
