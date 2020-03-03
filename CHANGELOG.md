# Changelog

All notable changes will be documented in this file

## 2.0.0 - 2020-03-04
- Simplify configuration for the production environment (#80)
- Add support for Laravel 7.0 (#82)

## 1.3.0 - 2020-02-24
- `NotificationFailed` event dispatched when a notification to a token is unsuccessful (#79)

## 1.2.0 - 2020-02-21
- You can now provide a configuration per-message if you want to use different clients (#52)
- You can now set a message expiry thanks to improved internal abstractions (#78)

## 1.1.1 - 2020-02-20
- Return the response value from the `send` method so it is available in the `NotificationSent` event (#76)
- Add `pushType` to support custom push types (#69)

## 1.1.0 - 2020-02-18
- Bump the minimum Pushok version (5ab28d108200b378ae477624948fb22f97d41356)

## 1.0.1 - 2020-02-18
- Support passing the token content in directly, instead of having to provide a path to the token file (fbc2ab66b199be383a1938fee040ec4f5cea4a08)
- Support the ability to provide additional configuration to the underlying `Token` class (fbc2ab66b199be383a1938fee040ec4f5cea4a08)

## 1.0.0 - 201X-02-18
- Replace Zend dependency with newer library called Pushok that supports .p8 tokens for authentication (#67)
