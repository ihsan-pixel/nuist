# TODO: Make Pengurus Role Access Same as Super Admin

## Information Gathered:
- Roles defined in migrations: 'super_admin', 'admin', 'tenaga_pendidik', 'pengurus', 'user'
- Routes in routes/web.php use middleware like 'role:super_admin,admin' or 'role:super_admin'
- IzinPolicy.php checks for 'super_admin' role in approve and reject methods

## Plan:
- Update routes/web.php to include 'pengurus' in middleware groups that currently allow 'super_admin'
- Update app/Policies/IzinPolicy.php to allow 'pengurus' the same permissions as 'super_admin'

## Dependent Files to be edited:
- routes/web.php
- app/Policies/IzinPolicy.php

## Followup steps:
- Test the changes to ensure 'pengurus' users have the same access as 'super_admin'
