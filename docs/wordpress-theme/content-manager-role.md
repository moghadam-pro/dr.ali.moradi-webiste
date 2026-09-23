# Content Manager role — theme 1.2.0

## Purpose

Use **Content Manager — مدیر محتوا** for the clinic operator instead of
Administrator. It is a theme-managed, least-privilege role intended for daily
editorial work.

## Allowed actions

- create, edit, publish, and trash Posts, including Posts written by another
  user;
- manage Post categories;
- upload and select Media Library files;
- create, edit, publish, and trash Team Members, because `team_member` uses
  WordPress's standard Post capabilities;
- open **Homepage Content** from the admin menu and publish changes to the
  English, Persian, and Arabic homepage settings.

## Explicitly excluded

The role does not receive capabilities for users, plugins, theme installation
or switching, general site options, the full Site Editor, code editing, or
updates. It also does not receive the broad `edit_theme_options` capability.

Customizer entry is granted by mapping WordPress's `customize` meta capability
to the dedicated primitive capability `dam_edit_homepage_content`. The Homepage
panel, each language section, and every Homepage setting enforce that same
capability. Other Customizer settings retain their normal capability checks.

## Provisioning an operator

1. Sign in as an Administrator.
2. Open **Users → Add New**.
3. Create a separate account for the operator; never share an Administrator
   account.
4. Select **Content Manager — مدیر محتوا** as the role.
5. Ask the operator to use a unique password and enable any account-security
   controls provided by the hosting environment.

## Role lifecycle

`inc/roles.php` stores a role-schema version in
`dam_content_manager_role_version`. On the first administrator request after a
theme release, the role is created or repaired to the declared capability set.
Administrators receive the dedicated Homepage capability so their existing
workflow is preserved. The role is not removed during a theme update or switch,
which avoids leaving assigned users without a role.

When changing the declared capability set, increment
`DAM_CONTENT_MANAGER_ROLE_VERSION` so existing installations are updated.

## Verification checklist

Test with a temporary user assigned only this role:

1. Posts, Media, Team Members, Categories, and Homepage Content are visible.
2. A draft Post and Team Member can be created and edited.
3. The Homepage Content button opens the three-language live editor and a test
   change can be previewed without publishing.
4. Users, Plugins, Appearance, Tools, Settings, and the full Site Editor are
   unavailable by menu and direct URL.
5. Remove the temporary user or change its role after the test.
