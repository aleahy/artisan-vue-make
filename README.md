# artisan-vue-make
An artisan command to create a vue component

## Installation

```bash
composer require --dev aleahy/artisan-vue-make
```

## Usage

### Creating a Component

```bash
php artisan make:vue NewComponent
```

This will create a new component file at `resources/js/components/NewComponent.vue`.

You can create subfolders for the component by using dot-notation.

```bash
php artisan make:vue path.to.NewComponent
```

This will create a new component file in `resources/js/components/path/to/NewComponent.vue

### Registering the component in app.js

The component can also be registered in the app.js file by assigning a tag for the component.

```bash
php artisan make:vue NewComponent --tag=new-component
```

By adding the `--tag` option, the following will be added to app.js after any previous component registration.
```javascript
Vue.component('new-component', require('./components/NewComponent').default);
```
