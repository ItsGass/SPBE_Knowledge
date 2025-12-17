<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\ActivityLog
 *
 * @property int $id
 * @property string $action
 * @property array|null $meta
 * @property int|null $performed_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog wherePerformedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereUpdatedAt($value)
 */
	class ActivityLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string|null $phone
 * @property string|null $division
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereDivision($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereUserId($value)
 */
	class Admin extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Knowledge
 *
 * @property int $id
 * @property string $title
 * @property string $body
 * @property int|null $scope_id
 * @property int $status_id
 * @property int $created_by
 * @property int|null $verified_by
 * @property string|null $attachment_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $verified_at
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\Scope|null $scope
 * @property-read \App\Models\Status $status
 * @property-read \App\Models\User|null $verifier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Knowledge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Knowledge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Knowledge query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Knowledge whereAttachmentPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Knowledge whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Knowledge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Knowledge whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Knowledge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Knowledge whereScopeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Knowledge whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Knowledge whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Knowledge whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Knowledge whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Knowledge whereVerifiedBy($value)
 */
	class Knowledge extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Knowledge> $knowledges
 * @property-read int|null $knowledges_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Scope newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Scope newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Scope query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Scope whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Scope whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Scope whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Scope whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Scope whereUpdatedAt($value)
 */
	class Scope extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $key
 * @property string $label
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Knowledge> $knowledges
 * @property-read int|null $knowledges_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Status newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Status newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Status query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Status whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Status whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Status whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Status whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Status whereUpdatedAt($value)
 */
	class Status extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperAdmin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperAdmin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperAdmin query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperAdmin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperAdmin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperAdmin whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperAdmin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuperAdmin whereUserId($value)
 */
	class SuperAdmin extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property-read \App\Models\Admin|null $adminProfile
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Knowledge> $knowledgeCreated
 * @property-read int|null $knowledge_created_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\SuperAdmin|null $superAdminProfile
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

