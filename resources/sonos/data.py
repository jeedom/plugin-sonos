import asyncio
from collections import OrderedDict

from soco.data_structures import SearchResult
from soco.alarms import Alarms

class SonosData:
    """Storage class for platform global data."""

    def __init__(self) -> None:
        """Initialize the data."""
        self.discovered: OrderedDict[str, any] = OrderedDict()
        self.favorites: SearchResult
        self.alarms = Alarms()
        self.topology_condition = asyncio.Condition()
        # self.hosts_heartbeat: CALLBACK_TYPE | None = None
        self.discovery_known: set[str] = set()
        # self.unjoin_data: dict[str, UnjoinData] = {}