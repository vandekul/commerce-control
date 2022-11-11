---
title: Form

form:
  name: sales
  action: /new-order
  fields:
    id:
        name: id
        label:  ID
        placeholder: Order Number
        default: SO
        autofocus: on
        autocomplete: on
        type: text
        validate:
          required: true
    order_date:
        name: order_date
        label:  Order Date
        placeholder: Order Number
        default:
        autofocus: on
        autocomplete: on
        type: date
        validate:
          required: true
    status:
        name: status
        type: select
        size: medium
        classes: fancy
        label: Status
        options:
          default: 'Quotation'
          paid: 'Paid'
          ready: 'Ready to send'
          sent: 'Sent'
          incident: 'Incident'
          closed: 'Closed'
        validate:
          required: true
    tabs:
      type: tabs
      active: 1
      fields:
        customer:
          type: tab
          title: Customer
          fields:
            address:
              name: address
              type: list
              style: vertical
              label: Address
              fields:
                type:
                  name: type
                  type: select
                  size: medium
                  classes: fancy
                  label: Address Type
                  options:
                    default: 'Shipping & Billing'
                    s1: 'Billing'
                    s2: 'Shipping'
                  validate:
                    required: true
                name:
                    name: name
                    label: Customer Name 
                    placeholder: Name 
                    autofocus: on
                    autocomplete: on
                    type: text
                surnames:
                    name: surnames
                    label: Customer Surnames 
                    placeholder: Surnames
                    autofocus: on
                    autocomplete: on
                    type: text
                street:
                    name: street
                    label: Street 
                    placeholder: Street 
                    autofocus: on
                    autocomplete: on
                    type: text
                zip:
                    name: zip
                    label: Zip 
                    placeholder: Zip 
                    autofocus: on
                    autocomplete: on
                    type: text
                country:
                    name: country
                    label: Country 
                    placeholder: Country 
                    autofocus: on
                    autocomplete: on
                    type: text
                phone:
                    name: phone
                    label: Phone 
                    placeholder: Phone Number 
                    autofocus: on
                    autocomplete: on
                    type: text
                email:
                  name: email
                  label: Email
                  placeholder: Enter your email address
                  default: susanna@gmail.com
                  type: email
        order:
          type: tab
          title: Order Lines
          fields:
            product:
              name: product
              type: list
              style: vertical
              label: Product
              fields:
                product_id:
                    name: product_id
                    label: ID Product
                    placeholder: 
                    type: select
                    data-options@: '\Grav\Plugin\CommerceControlPlugin::pagesProducts'
                product_id2:
                    name: produt_id2
                    label: Product2
                    placeholder: 
                    type: pages
                    start_route: '/shop'
                quantity:
                    name: quantity
                    label: Quantity 
                    placeholder: Quantity
                    autofocus: on
                    autocomplete: on
                    type: number
                tax:
                    name: tax
                    label: Tax 
                    placeholder: Tax
                    autofocus: on
                    autocomplete: on
                    type: number
  buttons:
    - type: submit
      value: Submit
    - type: reset
      value: Reset
  process:
    - save:
        fileprefix: '/quotations/{{ form.value.id|e }}'
        dateformat: Ymd
        extension: yaml
        body: "{% include 'forms/order.txt.twig' %}"
        operation: create
---
