import asyncio
from collections import OrderedDict

from .alarms import SonosAlarms

from soco.data_structures import SearchResult

class SonosData:
    """Storage class for platform global data."""

    def __init__(self) -> None:
        """Initialize the data."""
        # OrderedDict behavior used by SonosAlarms
        self.discovered: OrderedDict[str, any] = OrderedDict()
        self.favorites: SearchResult
        self.alarms: dict[str, SonosAlarms] = {}
        self.topology_condition = asyncio.Condition()
        # self.hosts_heartbeat: CALLBACK_TYPE | None = None
        self.discovery_known: set[str] = set()
        # self.unjoin_data: dict[str, UnjoinData] = {}