"""Sonos specific exceptions."""


class UnknownMediaType(Exception):
    """Unknown media type."""


class SonosSubscriptionsFailed(Exception):
    """Subscription creation failed."""


class SonosUpdateError(Exception):
    """Update failed."""


class S1BatteryMissing(SonosUpdateError):
    """Battery update failed on S1 firmware."""
